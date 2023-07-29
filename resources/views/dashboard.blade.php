@extends('layout.template_admin')

@section('css_jquery')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js">    </script>
@endsection



@section('content')
<!-- Main content -->
<section class="content">
  <div class="container-fluid">
    <!-- Small boxes (Stat box) -->
    <!-- /.row -->
    <!-- Main row -->
    <!-- ./row -->
    <div class="row">
      <div class="col-12 col-sm-12">
        <!-- general form elements -->
        <div class="card card-primary">
          <div class="card-header">
            <h3 class="card-title"></h3>

          </div>
          <!-- /.card-header -->
          <!-- form start -->
        </div>
        <!-- /.card -->
      </div>
    </div>
    <!-- /.row (main row) -->
  </div><!-- /.container-fluid -->
</section>
<!-- /.content -->


@endsection


<!-- /.kode javascript -->
@section('jascript')

<!-- AdminLTE App -->
<script src="AdminLTE-3.2.0/dist/js/adminlte.min.js"></script>

<script>
  function previewImg() {
    const flyer = document.querySelector('#fotoFlyer');
    const flyerLabel = document.querySelector('.fotoFlyer-label');
    const imgPreview = document.querySelector('.img-preview');

    flyerLabel.textContent = flyer.files[0].name;
    const fileFlyer = new FileReader();
    fileFlyer.readAsDataURL(flyer.files[0]);
    fileFlyer.onload = function(e) {
      imgPreview.src = e.target.result;
    }
  }

  function previewPPT() {
    const ppt = document.querySelector('#source_ppt');
    const pptLabel = document.querySelector('.ppt-label');

    pptLabel.textContent = ppt.files[0].name;
    //const filePpt = new FileReader();
    //filePpt.readAsDataURL(ppt.files[0]);

  }
</script>
@endsection
<!-- /.end kode javascript -->