<script language="JavaScript">
function payment_return(tranid, amount, service_code) {
    document.getElementById("TRANSACTION_ID").value = tranid;
    document.getElementById("AUTH_AMOUNT").value = amount;
    document.getElementById("SERVICE_CODE").value = service_code;

    document.getElementById("forderform").target = "_self";
    document.getElementById("forderform").action = "<?php echo $order_action_url; ?>";
    document.getElementById("forderform").submit();
}
</script>