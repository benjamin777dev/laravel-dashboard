<table class="table table-nowrap align-middle mb-0">
    <tbody>
        @if (count($upcomingTasks) > 0)
            @foreach ($upcomingTasks as $task)
                @include('task.partials.task_row', ['task' => $task])
            @endforeach
        @else
            <tr>
                <td class="text-center" colspan="5">No upcoming tasks found</td>
            </tr>
        @endif
    </tbody>
</table>