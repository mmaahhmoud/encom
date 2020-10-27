@extends('layouts.admin')
@section('content')
@can('visitor_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.visitors.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.visitor.title_singular') }}
            </a>
        </div>
    </div>
@endcan
<div class="card">
    <div class="card-header">
        {{ trans('cruds.visitor.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-Visitor">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            {{ trans('cruds.visitor.fields.id') }}
                        </th>
                        <th>
                            {{ trans('cruds.visitor.fields.visitor_id_num') }}
                        </th>
                        <th>
                            {{ trans('cruds.visitor.fields.visitor_name') }}
                        </th>
                        <th>
                            {{ trans('cruds.visitor.fields.mobile_num') }}
                        </th>
                        <th>
                            {{ trans('cruds.visitor.fields.governorate') }}
                        </th>
                        <th>
                            {{ trans('cruds.visitor.fields.dept') }}
                        </th>
                        <th>
                            {{ trans('cruds.visitor.fields.in_date_time') }}
                        </th>
                        <th>
                            {{ trans('cruds.visitor.fields.out_date_time') }}
                        </th>
                        <th>
                            {{ trans('cruds.visitor.fields.deposit') }}
                        </th>
                        <th>
                            {{ trans('cruds.visitor.fields.notes') }}
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($visitors as $key => $visitor)
                        <tr data-entry-id="{{ $visitor->id }}">
                            <td>

                            </td>
                            <td>
                                {{ $visitor->id ?? '' }}
                            </td>
                            <td>
                                {{ $visitor->visitor_id_num ?? '' }}
                            </td>
                            <td>
                                {{ $visitor->visitor_name ?? '' }}
                            </td>
                            <td>
                                {{ $visitor->mobile_num ?? '' }}
                            </td>
                            <td>
                                {{ $visitor->governorate->governorate_name ?? '' }}
                            </td>
                            <td>
                                {{ $visitor->dept->dept_name ?? '' }}
                            </td>
                            <td>
                                {{ $visitor->in_date_time ?? '' }}
                            </td>
                            <td>
                                {{ $visitor->out_date_time ?? '' }}
                            </td>
                            <td>
                                {{ $visitor->deposit ?? '' }}
                            </td>
                            <td>
                                {{ $visitor->notes ?? '' }}
                            </td>
                            <td>
                                @can('visitor_show')
                                    <a class="btn btn-xs btn-primary" href="{{ route('admin.visitors.show', $visitor->id) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan

                                @can('visitor_edit')
                                    <a class="btn btn-xs btn-info" href="{{ route('admin.visitors.edit', $visitor->id) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endcan

                                @can('visitor_delete')
                                    <form action="{{ route('admin.visitors.destroy', $visitor->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <input type="submit" class="btn btn-xs btn-danger" value="{{ trans('global.delete') }}">
                                    </form>
                                @endcan

                            </td>

                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>



@endsection
@section('scripts')
@parent
<script>
    $(function () {
  let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
@can('visitor_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.visitors.massDestroy') }}",
    className: 'btn-danger',
    action: function (e, dt, node, config) {
      var ids = $.map(dt.rows({ selected: true }).nodes(), function (entry) {
          return $(entry).data('entry-id')
      });

      if (ids.length === 0) {
        alert('{{ trans('global.datatables.zero_selected') }}')

        return
      }

      if (confirm('{{ trans('global.areYouSure') }}')) {
        $.ajax({
          headers: {'x-csrf-token': _token},
          method: 'POST',
          url: config.url,
          data: { ids: ids, _method: 'DELETE' }})
          .done(function () { location.reload() })
      }
    }
  }
  dtButtons.push(deleteButton)
@endcan

  $.extend(true, $.fn.dataTable.defaults, {
    orderCellsTop: true,
    order: [[ 1, 'desc' ]],
    pageLength: 100,
  });
  let table = $('.datatable-Visitor:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
})

</script>
@endsection