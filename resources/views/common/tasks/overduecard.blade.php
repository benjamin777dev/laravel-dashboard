<table class="table table-nowrap align-middle mb-0">
    <tbody>
        @if (count($inProgressTasks) > 0)
            @foreach ($inProgressTasks as $task)
                @include('task.partials.task-row', ['task' => $task])
            @endforeach
        @else
            <tr>
                <td class="text-center" colspan="5">No tasks in progress found</td>
            </tr>
        @endif
    </tbody>
</table>