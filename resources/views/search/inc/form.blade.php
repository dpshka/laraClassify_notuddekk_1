<?php
// Keywords
$keywords = rawurldecode(request()->get('q'));
$option = rawurldecode(request()->get('cf[0]'));
$optionvalue = rawurldecode(request()->get('cf[value]'));
// Category
$qCategory = (isset($cat) and !empty($cat)) ? $cat->id : request()->get('c');

// Location
if (isset($city) and !empty($city)) {
	$qLocationId = (isset($city->id)) ? $city->id : 0;
	$qLocation = $city->name;
	$qAdmin = request()->get('r');
} else {
	$qLocationId = request()->get('l');
	$qLocation = (request()->filled('r')) ? t('area') . rawurldecode(request()->get('r')) : request()->get('location');
    $qAdmin = request()->get('r');
}
?>
<div class="container">
	<div class="search-row-wrapper rounded">
		<div class="container">
		<form id="search" name="search" action="{{ \App\Helpers\UrlGen::search() }}" method="GET">
			<div class="row m-0">
				@foreach($field as $fld)
				<?php
					$fieldId = 'cf.' . $fld->id;
					$fieldName = 'cf[' . $fld->id . ']';
					$fieldOptions = $fld->options()->get();
				?>
				@if(in_array($fld->name,['Breidd','Hæð','Felgustærð','Framleiðandi','Týpa']))
				<div class="col-md-4 col-sm-6 mb-1 mb-xl-0 mb-lg-0 mb-md-0 search-col relative">
					<!-- <input type="text" name="option" class="form-control keyword has-icon" placeholder="{{ 'option' }}" value=""> -->
					<select name="{{ $fieldName }}" id="{{ $fieldId }}" class="form-control " style="border:1px solid #4682b4; border-radius:3px !important;" >
						<option value="" selected="selected" >
							{{ $fld->name }}
						</option>
						@if ($fld->options->count() > 0)
							@foreach ($fld->options as $itemOpt)
								<option value="{{ $itemOpt->id }}" >
									{{ $fld->name.' :  '.$itemOpt->value }}
								</option>
							@endforeach
						@endif
					</select>
				</div>
				@endif
				@endforeach
				
				<div class="col-md-4 col-sm-12 search-col">
					<button class="btn btn-primary btn-search btn-block" style="border:2px solid white">
						<i class="icon-search"></i> <strong>{{ t('find') }}</strong>
					</button>
				</div>
			</div>
			
		</form>
		</div>
	</div>
</div>

@section('after_scripts')
	@parent
	<script>
		$(document).ready(function () {
			$('#locSearch').on('change', function () {
				if ($(this).val() == '') {
					$('#lSearch').val('');
					$('#rSearch').val('');
				}
			});
		});
	</script>
@endsection
