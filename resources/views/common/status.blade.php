@if (Session::has('success'))
<div class="alert alert-success alert-dismissible show flex items-center mb-2" role="alert"> 
	<i data-lucide="check-circle" class="w-6 h-6 mr-2"></i>
	Yeay, Berhasil! {{ Session::get('success') }}
	<button type="button" class="btn-close" data-tw-dismiss="alert" aria-label="Close"> <i data-lucide="x" class="w-4 h-4"></i> </button> 
</div>
@endif
@if (Session::has('error'))
<div class="alert alert-warning alert-dismissible show flex items-center mb-2" role="alert"> 
	<i data-lucide="alert-circle" class="w-6 h-6 mr-2"></i>
	Waduh! Ada yang ga bener nih! {{ Session::get('error') }}
	<button type="button" class="btn-close" data-tw-dismiss="alert" aria-label="Close"> <i data-lucide="x" class="w-4 h-4"></i> </button> 
</div>
@endif