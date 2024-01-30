<tr>
    <td>{{ $course->course->coursecode }} / {{ $course->course->coursename }}</td>
    <td>
        @if ($isEdit == $course->companycourseid)
            <input type="text" wire:model="course.ratepeso">
            @error('course.ratepeso') <small class="text-danger">{{$message}}</small> @enderror
        @else    
            {{ $course->ratepeso }}
        @endif
    </td>
    <td>
        @if ($isEdit == $course->companycourseid)
            <input type="text" wire:model="course.rateusd">
            @error('course.rateusd') <small class="text-danger">{{$message}}</small> @enderror
        @else    
        {{ $course->rateusd }}
        @endif
    </td>
    <td>
        @if ($isEdit)
                <button class="btn btn-primary btn-sm" wire:click="update({{$course->companycourseid}})" title="Save">
                    <i class="bi bi-save dropdown-item-icon" style="color: white;"></i>
                </button>

                <button class="btn btn-danger btn-sm"  wire:click="closeEdit()"  title="Cancel">
                    <i class="bi bi-x dropdown-item-icon" style="color: white;"></i>
                </button>
        @else    
                <button  class="btn btn-primary btn-sm" wire:click="edit({{ $course->companycourseid }})" title="Edit">
                    <i class="fe fe-edit dropdown-item-icon" style="color: white;"></i>
                </button>
        
                <button  class="btn btn-danger btn-sm"  wire:click="delete({{ $course->companycourseid }})"  title="Delete">
                    <i class="bi bi-trash dropdown-item-icon" style="color: white;"></i>
                </button>
        @endif
    </td>
</tr>