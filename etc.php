fire

📉 📊 📈


// 엑셀

<script src="<?=G5_URL?>/excel/tabletoexcel/xlsx.core.min.js"></script>
<script src="<?=G5_URL?>/excel/tabletoexcel/FileSaver.min.js"></script>
<script src="<?=G5_URL?>/excel/tabletoexcel/tableExport.js"></script>


<input type="button" class="btn_submit excel" id="btnExport"  data-name='zeta_order_list' value="엑셀 다운로드" />



$(document).ready(function(){
        $("#btnExport").on('click',function(){
            var filename = $(this).data('name');
            $("#table").tableExport({
                separator: ",",
                headings: true,
                buttonContent: "Export",
                addClass: "", 
                defaultClass: "btn",
                defaultTheme: "btn-default",
                type: "excel",
                fileName: filename,
                position: "bottom",
                stripQuotes: true 
            });
        })
    });