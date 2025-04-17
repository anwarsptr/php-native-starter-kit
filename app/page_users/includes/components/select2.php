<link rel="stylesheet" href="assets/bower_components/select2/dist/css/select2.min.css">
<script src="assets/bower_components/select2/dist/js/select2.full.min.js"></script>
<style>
select.select2, .select2.select2-container { width: 100% !important; }
.select2-container--default .select2-selection--single { border: 1px solid #d2d6de; border-radius: 10px; }
.select2-container .select2-selection--single { height: 35px; }
.select2-container--default .select2-selection--single .select2-selection__arrow { top: 4px; right: 6px; }
.select2-container .select2-selection--single .select2-selection__rendered { padding-left: 0px; }
</style>
<script type="text/javascript">
$(".select2").select2({
  tags : false,
  placeholder: "-- Select --",
  width: '100%',
});
</script>
