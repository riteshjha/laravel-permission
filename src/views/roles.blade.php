@extends('permission::layouts.main')

@section('pageTitle')
  Roles
@endsection

@section('content')
    <div class="card">
        <div class="card-header d-flex align-items-center justify-content-between">
            <h5>Roles</h5>
        </div>
        <table class="table table-hover table-sm mb-0 penultimate-column-right">
            <thead>
                <tr>
                    <th scope="col" class="table-fit">Label</th>
                    <th scope="col">Name</th>
                    <th scope="col">Group</th>
                    <th scope="col" class="table-fit">Total Users</th> 
                    <th scope="col">Action</th> 
                </tr>
            </thead>
            <tbody>
                @forelse ($items as $role)
                    <tr>
                        <td class="table-fit">{{ $role->label }}</td>
                        <td>{{ $role->name }}</td>
                        <td> <label class="badge font-weight-light {{ $role->group == 1 ? 'badge-danger' : 'badge-primary' }}">{{ $roleGroups[$role->group] ?? '-' }}</label></td>
                        <td class="table-fit">{{ $role->users()->count() }}</td>
                        <td>
                            @if(!$role->isSuperAdmin())
                                <a href="{{ route('permission.roleAbilities', $role->id) }}" class="badge"><i class='fas fa-lock fa-lg fa-fw'></i></a>
                            @else
                                --
                            @endif
                        </td>
                    </tr>
                @empty 
                    <tr><td colspan="5">No Roles found</td></tr>
                @endforelse
            </tbody>
            @if ($items->hasPages())
                <tfoot>
                    <tr>
                        <th colspan="2">
                            <span class="text pull-right">Showing {{ $items->firstItem() }}  to {{ $items->lastItem() }} of {{ $items->total() }} entries</span>
                        </th>
                        <th colspan="3">
                            {!! $items->links() ?? '' !!}
                        </th>
                    </tr>
                </tfoot>
            @endif
        </table>
    </div>
@endsection