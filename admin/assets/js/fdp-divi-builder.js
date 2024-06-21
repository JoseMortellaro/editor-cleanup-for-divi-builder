jQuery(document).ready(function($){
  $(".eos-dp-save-eos_dp_ecfdb_" + fdp_divi_builder.page).on("click", function () {
    $('.eos-dp-opts-msg').addClass('eos-hidden');
    var chk,str = '';
    $('.eos-dp-divi-builder').each(function(){
      chk = $(this);
      str += !chk.is(':checked') ? ',' + $(this).attr('data-path') : ',';
    });
    eos_dp_send_ajax($(this),{
      "nonce" : $("#eos_dp_divi_builder_" + fdp_divi_builder.page + "_setts").val(),
      "eos_dp_divi_builder_data" : str,
      "page" : fdp_divi_builder.page,
      "action" : 'eos_dp_save_divi_builder_' + fdp_divi_builder.page + '_settings'
    });
    return false;
  });
});
