@extends('permission::layouts.main')

@section('pageTitle')
  Permissions
@endsection

@section('content')

<div class="container-fluid">
    <div class="row  mb-2">
        <div class="col-sm-2">
            <select id="roles" class="form-control mr-2  ml-2">
                @foreach ($roles as $role)
                    <option {{ $selectedRoleId == $role->id ? 'selected' : ''}} value="{{ $role->id }}">{{ $role->label ?? $role->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-sm-10">
            <button type="button" class="btn btn-primary pull-right" id="updatePermission">Update</button>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <form id="permissionsListForm">
                @csrf
                <table class="table">
                    <tr name="columns">
                        <th style="width:100px">Group</th>
                        <th>Ability</th>
                        <th style="width:100px">Permission</th>
                    </tr>

                    @forelse ($items as $ability)
                        <tr>
                            <?php $abilityRole = $ability->roles->first(); ?>
                            <td><label class="btn btn-sm {{ $ability->isSystemGroup() ? 'btn-danger' : 'btn-primary' }}">{{ $roleGroups[$ability->group] ?? '-' }}</label></td>
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
                </table>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    $(document).ready(function(){
        $('#roles').on('change', function() {
            window.location.href="/permission/roles/" + this.value + '/abilities';
        });

        $('#updatePermission').on('click', function(){
            let self = this;

            $(this).text('Loading...').attr('disabled', true);

            $.post('{{ route("permission.updateRoleAbility", $selectedRoleId) }}',$('#permissionsListForm').serialize(),  function(response){
            }, 'json').fail(function(response) {
                alert( "error" );
            }).always(function() {
                $(self).removeAttr('disabled').text('Update');
            });
        });
    });

</script>

@endsection