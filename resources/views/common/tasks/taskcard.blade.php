<table class="table table-nowrap align-middle mb-0">
<tbody>
    @if (count($overdueTasks) > 0)
        @foreach ($overdueTasks as $task)
            @include('task.partials.task-row', ['task' => $task])
        @endforeach
    @else
        <tr>
            <td class="text-center" colspan="5">No overdue tasks found</td>
        </tr>
    @endif
</tbody>
</table>