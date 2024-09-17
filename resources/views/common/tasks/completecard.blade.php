<table class="table table-nowrap align-middle mb-0">
    <tbody>
        @if (count($completedTasks) > 0)
            @foreach ($completedTasks as $task)
                @include('task.partials.task-row', ['task' => $task])
            @endforeach
        @else
            <tr>
                <td class="text-center" colspan="5">No completed tasks found</td>
            </tr>
        @endif
    </tbody>
</table>