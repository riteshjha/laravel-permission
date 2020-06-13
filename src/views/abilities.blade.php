@extends('permission::layouts.main')

@section('pageTitle')
  Abilities
@endsection

@section('topButtons')
    <button title="Sync" class="btn btn-outline-primary ml-auto" id="sync" onclick="syncAbility()"><i class="fas fa-sync"></i></button>
@endsection

@section('content')
    <div class="card">
        <div class="card-header d-flex align-items-center justify-content-between">
            <h5>Abilities</h5>
            <form>
                <input class="form-control" type="search" name="{{ $searchKey }}" value="{{ request($searchKey, '') }}" placeholder="Search" aria-label="Search">
            </form>
        </div>
        <table class="table table-hover table-sm mb-0 penultimate-column-right">
            <thead>
                <tr>
                    <th scope="col" class="table-fit">Group</th>
                    <th scope="col">Name</th>
                    <th scope="col">Label</th>
                    <th scope="col">Assigned</th> 
                </tr>
            </thead>
            <tbody>
                @forelse ($items as $ability)
                    <tr>
                        <?php $roles = $ability->roles()->wherePivot('level','<>', 0)->get() ?>
                        <td><label class="badge font-weight-light {{ $ability->group == 1 ? 'badge-danger' : 'badge-primary' }}">{{ $roleGroups[$ability->group] ?? '-' }}</label></td>
                        <td>{{ $ability->name }}</td>
                        <td><a href="javascript:void(0)" class="editable" id="label" data-type="text" data-pk="{{ $ability->id }}" data-url="{{ route('permission.updateAbility', ['id' => $ability->id]) }}" data-title="Enter Label">
                            {{ $ability->label ?? '--' }}</a>
                        </td>
                        <td>{{ ($roles->count() > 0) ? $roles->implode('name', ',') : '--' }}</td>
                    </tr>
                @empty 
                    <tr><td colspan="{{ $noCols ?? '' }}">{{ $empty ?? '' }}</td></tr>
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

@section('styles')
    <link href="{{ asset('vendor/permission/js/x-editable/bootstrap-editable.css') }}" rel="stylesheet">
@endsection

@section('scripts')
    <script src="{{ asset('vendor/permission/js/x-editable/bootstrap-editable.min.js') }}" type="text/javascript"></script>
    <script>
        $(document).ready(function() {
            $('.editable').editable();
        });

        function syncAbility(){
            $('#sync').html('<i class="fas fa-sync fa-spin"></i>').attr('disabled', true);

            $.get('{{ route("permission.recordAbilities") }}', function(response){
                window.location.reload(true);
            }).always(function() {
                $('#sync').removeAttr('disabled').html('<i class="fas fa-sync"></i>');
            });
        }
    </script>

@endsection
