<div wire:ignore.self class="modal fade" id="exampleModalScrollable" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalScrollableTitle">IMPORTANT REMINDER</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <div class="modal-body">
                <p>1. All classes are conducted from 0800H to 1700H.</p>
                <p>2. Please observe the proper dress code (polo shirt, dark slacks, black leather shoes). Wearing maong pants and rubber shoes is strictly prohibited.</p>
                <p>3. Discipline, good manners, and right conduct should be observed at all times. Littering, loitering, sleeping, smoking, and lack of self-control due to the influence of alcohol are grounds for termination of training.</p>
                <p>4. If you will avail the shuttle service, please arrive on time at NYK-FIL Intramuros; the bus shall leave at exactly 0645H every Monday to Friday.</p>
                <p>5. For those who will undergo a Rapid test, please be at TMDC on or before 0600H.</p>
                <p>6. 'No admission slip and No Rapid Test result (negative) - no boarding of the bus' policy shall apply. For those who will go directly to NETI, you may present a digital copy of your admission slip upon entry.</p>
                <p>7. All crews are required to scan the Safety Seal QR Code found at the entrance for health declaration purposes.</p>
                <p>8. NETI Safety Briefing and Orientation:</p>
                <div class="indent">
                    <p><b>FOR TRAINEES ARRIVING ON MONDAY AND WEDNESDAY –</b> Upon arrival at the training center, please proceed to the NDB 2nd Floor.</p>
                </div>
                <div class="indent">
                    <p><b>FOR OTHER TRAINEES –</b> Safety briefing shall be conducted on the bus through video streaming (insert link here) and may proceed immediately to your assigned classroom.</p>
                </div>
                <p>7. 'No photo, no issuance of the training certificate' is strictly observed to avoid delay. For your convenience, you may upload your photos in your NETI OES account or you can have your photos taken on-site at our photo center.</p>
                <p>8. Cancellation of enrollment shall be made through the online enrollment system seven (7) working days prior to the start of your training. In case you cannot cancel it online due to unavoidable circumstances, you may reach us by calling (02) 8-908-4900 /(02) 8-554- 3888. The cutoff date for issuance of an admission slip is every Tuesday.</p>
                <p>9. For walk-in trainees, payment should be settled in full at least one day before your first day of training. Payments are strictly online.</p>

                <h4 for="terms_conditions" class="form-check-label">
                    <input type="checkbox" id="terms_conditions" class="form-check-input" wire:model="acceptTerms" required>
                    If you click this button, you acknowledge that you have read and understand this important reminders.
                </h4>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" wire:click.prevent="create" wire:loading.attr="disabled" class="btn btn-success" @if(!$acceptTerms) disabled @endif>
                    I understand and proceed
                </button>
            </div>
        </div>
    </div>
</div>