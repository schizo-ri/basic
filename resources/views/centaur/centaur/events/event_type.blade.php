<div class="event_show">
    <a class="btn btn-primary btn-lg btn-new" href="{{ route('events.create',['type', 'event']) }}"  rel="modal:open">
        <div class="" >
            <h3>Add event</h3>
            <p>Creat your event, and add other users</p>
        </div>
    </a>
    <a class="btn btn-primary btn-lg btn-new" href="{{ route('tasks.create',['type', 'tast']) }}"  rel="modal:open">
        <div>
            <h3>Add task</h3>
            <p>Save any task so you don’t miss any</p>
        </div>
    </a>
    <a class="btn btn-primary btn-lg btn-new" href="{{ route('events.create',['type', 'other']) }}"  rel="modal:open">
        <div>
            <h3>Add other events</h3>
            <p>Add celebrating, birthdays for office parties</p>
        </div>
    </a>
</div>