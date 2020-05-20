@extends('permission::layouts.main')

@section('pageTitle')
  Roles
@endsection

@section('content')

    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <table class="table">
                    <tr>
                        <th>Label</th>
                        <th>Name</th>
                        <th>Group</th>
                        <th>Total Users</th> 
                        <th>Action</th> 
                    </tr>
                    @forelse ($items as $role)
                        <tr>
                            <td>{{ $role->label }}</td>
                            <td>{{ $role->name }}</td>
                            <td> <label class="btn btn-sm {{ $role->group == 1 ? 'btn-danger' : 'btn-primary' }}">{{ $roleGroups[$role->group] ?? '-' }}</label></td>
                            <td>{{ $role->users()->count() }}</td>
                            <td>
                                @if(!$role->isSuperAdmin())
                                    <a href="{{ route('permission.roleAbilities', ['role' => $role]) }}" class="btn btn-info"><i class='fa fa-lock'></i></a>
                                @else
                                    --
                                @endif
                            </td>
                        </tr>
                    @empty 
                        <tr><td colspan="5">No Roles found</td></tr>
                    @endforelse
                </table>
            </div>
        </div>
        
        @if ($items->hasPages())
            <div class="row">
                <div class="col-sm-12 col-md-6">
                    <span class="text">Showing {{ $items->firstItem() }}  to {{ $items->lastItem() }} of {{ $items->total() }} entries</span>
                </div>
                <div class="col-sm-12 col-md-6 float-right">
                        {!! $links ?? '' !!}
                </div>
            </div>
        @endif
    </div>
@endsection