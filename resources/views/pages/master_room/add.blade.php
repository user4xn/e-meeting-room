<div class="col app-calendar-content">
    <div class="offcanvas offcanvas-end event-sidebar" tabindex="-1" id="addRoom" aria-labelledby="addEventSidebarLabel">
        <div class="offcanvas-header my-1">
            <h5 class="offcanvas-title" id="addEventSidebarLabel">Tambah Ruangan</h5>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body pt-0">
            <form class="event-form pt-0" method="POST" id="eventForm" action="{{route('master-room.updateOrCreate')}}">
                @csrf
                <input type="hidden" name="master_room_id" id="master_room_id">
                <div class="mb-3">
                    <label class="form-label" for="room_name">Nama Ruangan</label>
                    <input type="text" class="form-control" id="room_name" name="room_name" placeholder="Ruangan X" required/>
                </div>
                <div class="mb-3">
                    <label class="form-label" for="room_location">Location</label>
                    <input type="text" class="form-control" id="room_location" name="room_location" placeholder="Gedung C, Lantai 1" required/>
                </div>
                <div class="mb-3">
                    <label class="form-label" for="room_description">Description</label>
                    <textarea class="form-control" name="room_description" id="room_description" placeholder="Gedung C, Lantai 1" required></textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label" for="room_capacity">Kapasitas</label>
                    <div class="room-number-counter">
                        <span class="minus btn btn-primary btn-sm"><i class="fa fa-minus"></i></span>
                        <input type="text" value="1" name="room_capacity" id="room_capacity" required/>
                        <span class="plus btn btn-primary btn-sm"><i class="fa fa-plus"></i></span>
                    </div>
                </div>
                <div class="mb-3 d-flex justify-content-sm-between justify-content-start my-4">
                    <div>
                        <button type="submit" id="submitEventBtn" class="btn btn-primary btn-add-event me-sm-3 me-1">Add</button>
                        <button type="reset" class="btn btn-label-secondary btn-cancel me-sm-0 me-1" data-bs-dismiss="offcanvas">
                            Cancel
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>