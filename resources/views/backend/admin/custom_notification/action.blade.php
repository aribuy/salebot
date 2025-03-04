<li><a href="javascript:void(0)"
       onclick="delete_row('{{ route('custom-notification.destroy', $notification ->id) }}')"
       data-toggle="tooltip"
       title="{{ __('delete') }}"><i
				class="las la-trash-alt"></i></a></li>