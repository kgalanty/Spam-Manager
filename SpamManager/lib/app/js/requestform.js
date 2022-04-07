$(document).ready(function () {
    $('select[name="reason"]').on('change', function()
{
    if($(this).val() == 'Other')
    {
        $('#othercancellationreason').fadeIn();
    }
    else
    {
        $('#othercancellationreason').fadeOut(100);
    }
});

    $('#othercancellationreason').fadeOut(100);
    $("#sendCancelReqBtn").on('click', function ()
    {
        $('.loader').show();
        const reason = $('[name="reason"]').val() === "Other" ? $('textarea[name="reason"]').val() : $('[name="reason"]').val()
        const type = $('#type').val()
        const canceldomain = typeof $('#canceldomain').val() === 'undefined' ? '' : $('#canceldomain').val()
        const skipemail = $('[name="skipemail"]').prop('checked') === true ? 1 : 0
        $.post('', {hid: $("input[name='id']").val(), action: 'saveCancelReq', reason: reason, type: type, domain: canceldomain, skipemail}).done(function (d) {
            $('.loader').hide();
            $('#cancelreqModal').modal('hide');
            jQuery.growl.notice({ title: 'Success', message: 'Request Sent' });
            window.location.reload()
        });
    });


    $("#frm1 > .form > tbody > tr:nth(8)").after('<tr id="adminnotesext"><td class="fieldlabel" style="width:20%">Cancellation Request</td><td class="fieldarea" colspan="3"><button type="button" class="btn btn-primary" id="addNewNotesModalOpen" data-toggle="modal" data-target="#cancelreqModal">Add Cancellation Request</button><span id="statusMsg" style="margin-left:15px"></span></td></tr>');
   // $("#frm1 > .form tr:nth(9)").after('');
    //$("#adminnotesext td.fieldarea").append('<table id="adminnotesdt" class="display" style="width:100%"><thead><tr><th>#</th><th>Admin Name</th><th>Date</th><th>Note</th><th>Actions</th></tr></thead></table>');

});

