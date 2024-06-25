@extends('permission::layouts.main')

@section('pageTitle')
  Permissions
@endsection

@section('topButtons')
    <button type="button" class="btn btn-outline-primary ml-auto" id="updatePermission"><i class="fas fa-edit"></i></button>
@endsection

@section('content')
    <div class="card">
        <div class="card-header d-flex align-items-center justify-content-between">
            <h5>Permissions</h5>
            <select id="roles" class="form-control w-15">
                @foreach ($roles as $role)
                    <option {{ $selectedRole->id == $role->id ? 'selected' : ''}} value="{{ $role->id }}">{{ $role->label ?? $role->name }}</option>
                @endforeach
            </select>
            <form>
                <input class="form-control" type="search" name="{{ $searchKey }}" value="{{ request($searchKey, '') }}" placeholder="Search" aria-label="Search">
            </form>
        </div>
        <table class="table table-hover table-sm mb-0 penultimate-column-right">
            <thead>
                <tr>
                    <th scope="col" class="table-fit">Group</th>
                    <th scope="col">Ability</th>
                    <th scope="col" class="w-15">Permission</th> 
                </tr>
            </thead>
            <form id="permissionsListForm">
                <tbody>
                    @forelse ($items as $ability)
                        <tr>
                            <?php $abilityRole = $ability->roles->first(); ?>
                            <td><label class="badge font-weight-light {{ $ability->isSystemGroup() ? 'badge-danger' : 'badge-primary' }}">{{ $roleGroups[$ability->group] ?? '-' }}</label></td>
                            <td>{{ $ability->label ?? $ability->name }}</td>
                            <td>
                                <select name="permissions[{{$ability->id}}][level]" class="form-control">
                                    <option value="0">None</option>
                                    @if($ability->isSystemGroup() || $ability->isFieldAbility())
                                        <option {{ $abilityRole && $abilityRole->pivot->level == 1 ? 'selected' : '' }} value="1">Allow</option>
                                    @else
                                        @foreach ($permissionLevels as $id => $permissionLevel)
                                            <option {{ $abilityRole && $abilityRole->pivot->level == $id ? 'selected' : '' }} value="{{ $id }}">{{ $permissionLevel }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="3">No abilities found</td></tr>
                    @endforelse
                </tbody>
            </form>
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

@section('scripts')
    <script>
        $(document).ready(function(){
            $('#roles').on('change', function() {
                window.location.href= '/{{ config('permission.adminPrefix')}}/' + "permission/roles/" + this.value + '/abilities';
            });

            $('#updatePermission').on('click', function(){
                let self = this;

                $(this).html('<i class="fas fa-sync fa-spin"></i>').attr('disabled', true);

                $.post("/{{ config('permission.adminPrefix')}}/permission/roles/{{ $selectedRole->id }}/abilities",$('#permissionsListForm').serialize(),  function(response){
                }, 'json').fail(function(response) {
                    alert( "error" );
                }).always(function() {
                    $(self).removeAttr('disabled').html('<i class="fas fa-edit"></i>');
                });
            });
        });
    </script>
@endsection
