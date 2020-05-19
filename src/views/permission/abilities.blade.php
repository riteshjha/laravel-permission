@extends(config('permission.view.layout'))

@section('pageTitle')
  Abilities
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <form id="abilityListForm">
                    @csrf
                    <div class="table">
                        @forelse ($items as $ability)
                            <tr>
                                <?php $roles = $ability->roles()->wherePivot('level','<>', 0)->get() ?>
                                <td><label class="btn btn-sm {{ $ability->group == 1 ? 'btn-danger' : 'btn-primary' }}">{{ $roleGroups[$ability->group] ?? '-' }}</label></td>
                                <td>{{ $ability->name }}</td>
                                <td><a href="#" class="editable" id="label" data-type="text" data-pk="{{ $ability->id }}" data-url="{{ route('persmission.updateAbility', ['ability' => $ability]) }}" data-title="Enter Label">
                                    {{ $ability->label ?? '--' }}</a>
                                </td>
                                <td>{{ ($roles->count() > 0) ? $roles->implode('name', ',') : '--' }}</td>
                            </tr>
                        @else 
                            <tr><td colspan="{{ $noCols ?? '' }}">{{ $empty ?? '' }}</td></tr>
                        @endforelse
                    </div>
                </form>
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

@section('pageHeadAssets')
<link href="{{ asset('js/plugins/x-editable/bootstrap-editable.css') }}" rel="stylesheet">
@endsection

@section('pageAssets')
<script src="{{ asset('js/plugins/x-editable/bootstrap-editable.min.js') }}"></script>
<script>
    $(document).ready(function() {
        $('.editable').editable();
    });

    function syncAbility(){
        $('#sync').text('Loading...').attr('disabled', true);

        $.get('{{ route("admin.syncAbilities") }}', function(response){
            window.location.reload(true);
        });
    }
</script>

@endsection