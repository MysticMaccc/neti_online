<?php

namespace App\Http\Livewire\Admin\Billing\Child\GenerateBilling;

use Livewire\Component;
use App\Models\billingattachment;
use App\Models\billingattachmenttype;
use Lean\ConsoleLog\ConsoleLog;
use Livewire\WithFileUploads;

class AddAttachmentModalComponent extends Component
{
    use WithFileUploads;
    use ConsoleLog;
    public $file;
    public $title;
    public $attachmenttypeid;
    public $companyid;
    public $scheduleid;

    protected $rules = [
        'file' => "required|mimes:jpg,png,pdf|max:2048",
        'title' => 'required',
        'attachmenttypeid' => 'required'
    ];

    public function render()
    {
        $attachmenttype_data = billingattachmenttype::where('id', '!=', '2')->get();

        return view('livewire.admin.billing.child.generate-billing.add-attachment-modal-component', compact('attachmenttype_data'));
    }

    public function upload()
    {
        $this->validate();
        try {
            $filepath = $this->file->storeAs('public/uploads/billingAttachment', $this->file->hashName());
            if ($filepath) {
                $add_attachment = new billingattachment();
                $add_attachment->scheduleid = $this->scheduleid;
                $add_attachment->companyid = $this->companyid;
                $add_attachment->title = $this->title;
                $add_attachment->filepath = $this->file->hashName();
                $add_attachment->attachmenttypeid = $this->attachmenttypeid;
                $add_attachment->is_deleted = 0;
                $create = $add_attachment->save();
                
                if($create){
                        $this->dispatchBrowserEvent('save-log', [
                            'title' => 'Uploaded Successfully'
                        ]);
                }

            }
            return redirect()->route('a.billing-viewtrainees');
        } catch (\Exception $e) {
            $this->consoleLog($e->getMessage());
        }
    }

}
