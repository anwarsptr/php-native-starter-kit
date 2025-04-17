<div class="row" id="vTable" style="display: none">
    <div class="col-md-12">
      <div class="box box-primary border-radius" style="padding:15px;">
        <div class="row">
            <div class="col-md-6">
                <?php if ($showPrintPreview) : ?>
                <button type="button" class="btn bg-navy btn-sm border-radius" id="btnPrint" style="display:none"><i class="fa fa-print"></i> Print</button>
                <?php endif ?>
                <?php if ($showExportExcel) : ?>
                <button type="button" class="btn btn-success btn-sm border-radius" id="btnExportExcel" style="display:none"><i class="fa fa-file-excel-o"></i> Export Excel</button>
                <?php endif ?>
                <?php if ($showExportPDF) : ?>
                <button type="button" class="btn btn-danger btn-sm border-radius" id="btnExportPDF" style="display:none"><i class="fa fa-file-pdf-o"></i> Export PDF</button>
                <?php endif ?>
            </div>
            <div class="col-md-6 text-right" style="padding-top:5px"><span id="select_tgl"></span></div>
        </div>
        <div style="max-height: 500px; overflow-y: auto;margin-top:10px">
            <div class="printArea" id="printArea">
                <div id="printHeader" style="display:none">
                    <b id="printHeaderTitle"><?= $app_name ?></b><br>
                    <span id="printHeaderSubtitle"><?= $title ?></span><br>
                    <span id="printHeaderTgl"></span>
                    <br>
                </div>
                <table id="dataTable" class="table table-bordered table-striped table-hover" width="100%">
                    <thead>
                        <tr>
                            <th class="text-center bg-primary text-white" width="5%">No</th>
                            <th class="text-center bg-primary text-white" width="10%">No&nbsp;Buku&nbsp;Tamu</th>
                            <th class="text-center bg-primary text-white" width="20%">Tanggal&nbsp;Kunjungan</th>
                            <th class="text-center bg-primary text-white" width="25%">Nama&nbsp;Tamu</th>
                            <th class="text-center bg-primary text-white" width="10%">No&nbsp;Telp</th>
                            <th class="text-center bg-primary text-white" width="20%">Jenis&nbsp;Identitas</th>
                            <th class="text-center bg-primary text-white" width="10%">No&nbsp;Kendaraan</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
      </div>
    </div>
</div>


<script type="text/javascript">
var tgl_1='';
var tgl_2='';
$(document).ready(function () {
  $("#from_date").datepicker({
        format: 'dd-mm-yyyy',
        autoclose: true,
    }).on('changeDate', function(selectedDate) {
        // Ketika from date diubah, perbarui minDate untuk to date
        $("#to_date").datepicker("setStartDate", selectedDate.date);

        // Cek apakah from date lebih besar dari to date
        if ($("#to_date").datepicker("getDate") < selectedDate.date) {
            // Jika from date lebih besar dari to date, atur to date menjadi sama dengan from date
            $("#to_date").datepicker("setDate", selectedDate.date);
        }
    });

    // Inisialisasi datepicker untuk to date
    $("#to_date").datepicker({
        format: 'dd-mm-yyyy',
        autoclose: true,
    });

    <?php if ($showPrintPreview) : ?>
    $("#btnPrint").click(function() {
        if (!$("#dataTable tbody tr").length) {
            swalResponse('warning', 'Tidak ada data yang bisa di <b>Print</b>!');
            return;
        }
        swalLoading();
        $('#printHeader').show();
        $("#printArea").printThis({
            // debug: true,
            importCSS: false,
            loadCSS: printThisCSS,
            beforePrint: function() {
                $('body').append('<style>@page { size: landscape; }</style>');
            },
            afterPrint: function () {
                $('#printHeader').hide();
                swalLoading('close'); // Pastikan loading tetap tersembunyi setelah cetak
            }
        });
    });
    <?php endif ?>

    <?php if ($showExportExcel) : ?>
    $("#btnExportExcel").click(function() {
        if (!$("#dataTable tbody tr").length) {
            swalResponse('warning', 'Tidak ada data yang bisa di <b>Export</b>!');
            return;
        }
        swalLoading();
        tgl_nya = (tgl_1==tgl_2) ? tgl_1:`${tgl_1} - ${tgl_2}`;
        var wb = new ExcelJS.Workbook();
        var ws = wb.addWorksheet('<?= $title ?>');

        var classToColor = {
            "bg-primary": "007BFF",
            "bg-secondary": "6C757D",
            "bg-success": "28A745",
            "bg-danger": "DC3545",
            "bg-warning": "FFC107",
            "bg-info": "17A2B8",
            "bg-light": "F8F9FA",
            "bg-dark": "343A40",
            "text-white": "FFFFFF",
            "text-dark": "000000"
        };

        var classToAlign = {
            "text-center": "center",
            "text-left": "left",
            "text-right": "right"
        };

        const borderStyle = {
            top: { style: 'thin', color: { argb: '000000' } },
            left: { style: 'thin', color: { argb: '000000' } },
            bottom: { style: 'thin', color: { argb: '000000' } },
            right: { style: 'thin', color: { argb: '000000' } }
        };

        // Baris pertama untuk judul
        var headerTextRow1 = [$('#printHeaderTitle').text()];
        var headerTextCell1 = ws.addRow(headerTextRow1);
        headerTextCell1.font = { bold: true, size: 12 };
        headerTextCell1.alignment = { horizontal: 'left', vertical: 'middle' };
        ws.mergeCells('A1:G1'); // Menggabungkan seluruh kolom A hingga G

        // Baris kedua untuk subtitle
        var headerTextRow2 = [$('#printHeaderSubtitle').text()];
        var headerTextCell2 = ws.addRow(headerTextRow2);
        headerTextCell2.font = { size: 11 };
        headerTextCell2.alignment = { horizontal: 'left', vertical: 'middle' };
        ws.mergeCells('A2:G2'); // Menggabungkan seluruh kolom A hingga G untuk baris subtitle

        // Baris kedua untuk tanggal
        var headerTextRow2 = [$('#printHeaderTgl').text()];
        var headerTextCell2 = ws.addRow(headerTextRow2);
        headerTextCell2.font = { size: 11 };
        headerTextCell2.alignment = { horizontal: 'left', vertical: 'middle' };
        ws.mergeCells('A3:G3'); // Menggabungkan seluruh kolom A hingga G untuk baris tanggal

        // Atur tinggi baris title
        ws.getRow(1).height = 25;

        ws.addRow([]);

        startIndexTable=5;

        // Menambahkan header ke worksheet berdasarkan thead
        var headerRow = [];
        $("#dataTable thead tr th").each(function (index, th) {
            headerRow.push($(th).text().trim());
        });
        ws.addRow(headerRow);

        // Gaya untuk header
        var headerStyle = {
            font: { bold: true, color: { argb: "FFFFFF" } },
            fill: { type: "pattern", pattern: "solid", fgColor: { argb: "3290EC" } },
            alignment: { horizontal: "center", vertical: "middle" },
            border: borderStyle
        };

        // Aplikasikan gaya untuk header
        headerRow.forEach(function (cell, index) {
            ws.getCell(startIndexTable, index + 1).style = headerStyle;
        });

        // Atur tinggi baris header
        ws.getRow(startIndexTable).height = 20; // Atur tinggi baris header

        // Set lebar kolom berdasarkan nilai yang ada di dalamnya
        var columnWidths = [5, 20, 20, 30,  20, 30, 20]; // Lebar kolom bisa disesuaikan
        columnWidths.forEach(function (width, index) {
            ws.getColumn(index + 1).width = width;
        });

        // Loop melalui setiap row dari tabel
        $("#dataTable tbody tr").each(function (rowIndex, row) {
            var rowData = [];
            $(row).find("td, th").each(function (colIndex, cell) {
                var cellText = $(cell).text().trim();
                rowData.push(cellText);
            });

            // Menambahkan data baris ke worksheet
            var rowExcel = ws.addRow(rowData);

            // Menambahkan warna berdasarkan class
            $(row).find("td, th").each(function (colIndex, cell) {
                var cellRef = rowExcel.getCell(colIndex + 1);
                var cellClass = $(cell).attr("class") || "";
                var bgColor = "FFFFFF";  // Default warna latar belakang
                var textColor = "000000";  // Default warna teks
                var textAlign = "left";

                // Cek class dan tentukan warna
                cellClass.split(" ").forEach(function (cls) {
                    if (classToColor[cls]) {
                        if (cls.includes("bg-")) {
                            bgColor = classToColor[cls];
                        } else if (cls.includes("text-")) {
                            textColor = classToColor[cls];
                        }
                    }
                    if (classToAlign[cls]) {
                        if (cls.includes("text-")) {
                            textAlign = classToAlign[cls];
                        }
                    }
                });

                // Set warna latar belakang dan warna teks
                cellRef.style = {
                    fill: { type: "pattern", pattern: "solid", fgColor: { argb: bgColor } },
                    font: { color: { argb: textColor } },
                    border: borderStyle,
                    alignment: {
                        horizontal: textAlign,
                        vertical: "middle"
                    }
                };
            });
        });

        // Menyimpan file Excel
        wb.xlsx.writeBuffer().then(function (buffer) {
            var blob = new Blob([buffer], { type: "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" });
            var link = document.createElement("a");
            link.href = URL.createObjectURL(blob);
            link.download = `<?= $title ?>-${$('#printHeaderTgl').text()}.xlsx`;
            link.click();
        });
        setTimeout(function() { swalLoading('close'); }, 1000);
    });
    <?php endif ?>

    <?php if ($showExportPDF) : ?>
    $("#btnExportPDF").click(function () {
        if (!$("#dataTable tbody tr").length) {
            swalResponse('warning', 'Tidak ada data yang bisa di <b>Export</b>!');
            return;
        }
        const { jsPDF } = window.jspdf;
        var doc = new jsPDF('l', 'mm', 'a4');

        headerTgl = $('#printHeaderTgl').text();

        // Judul PDF
        doc.setFontSize(10);
        doc.setFont("helvetica", "bold");
        doc.text($('#printHeaderTitle').text(), 14, 15);
        doc.setFont("helvetica", "normal");
        doc.setFontSize(9);
        doc.text($('#printHeaderSubtitle').text(), 14, 20);
        if (headerTgl!="") {
            doc.setFontSize(9);
            doc.text(headerTgl, 14, 25);
        }

        var headerRow = [];
        $("#dataTable thead tr th").each(function (index, th) {
            headerRow.push($(th).text().trim());
        });

        // Ambil data dari tabel
        var data = [];
        $("#dataTable tbody tr").each(function () {
            var row = [];
            $(this).find("td").each(function () {
                row.push($(this).text().trim());
            });
            data.push(row);
        });

        // Konfigurasi tabel
        doc.autoTable({
            head: [headerRow],
            body: data,
            theme: 'grid',
            startX: 15,
            startY: headerTgl=="" ? 25:30,
            styles: { fontSize: 8, textColor: 0, lineColor: 200, lineWidth: 0.1 },
            headStyles: { fontSize: 9, fillColor: [50, 144, 236], textColor: 255, halign: 'center' },
            columnStyles: {
                0: { halign: 'center' },
                1: { halign: 'center' },
                2: { halign: 'center' },
                3: { halign: 'left' },
                4: { halign: 'left' },
                5: { halign: 'left' },
                6: { halign: 'left' },
                7: { halign: 'left' },
                8: { halign: 'left' },
                9: { halign: 'left' }
            },
        });

        // Simpan PDF
        doc.save(`<?= $title ?>-${$('#printHeaderTgl').text()}.pdf`);
    });
    <?php endif ?>
});

function showDataFilter() {
    let fromDate = $("#from_date").datepicker("getDate");
    let toDate = $("#to_date").datepicker("getDate");

    if (fromDate && toDate) {
        tgl_1 = $("#from_date").val();
        tgl_2 = $("#to_date").val();
    }else{
        swalResponse('warning', "'<b>Dari Tanggal</b>' & '<b>Sampai Tanggal</b>' wajib diisi!");
        return;
    }

    var fd = new FormData();
    fd.append('from', tgl_1);
    fd.append('to', tgl_2);
    fd.append('nama_pengunjung', $('#nama_pengunjung').val());

    prosesSubmit({
        csrf: true, method: "POST",
        url: `<?= $url_proses ?>get`, fd:fd,
        callbackSuccess: function(response) {
            data = response.message;
            tgl = (tgl_1==tgl_2) ? `<b>${tgl_1}</b>`:`<b>${tgl_1}</b> s/d <b>${tgl_2}</b>`;
            $('#select_tgl').html(`Tanggal : ${tgl}`);
            $('#printHeaderTgl').html(`Tanggal : ${tgl}`);
            dataBody = $('#dataTable tbody');
            dataBody.empty(); no=1;
            $.each(data, function( key, value ) {
                tgl = value.tgl_kunjungan ? format_tglnya(value.tgl_kunjungan, 'dd-mm-yyyy'):'';
                dataBody.append(`
                    <tr>
                        <td class="text-center">${no}</td>
                        <td class="text-center">${value.nomor}</td>
                        <td class="text-center">${tgl}</td>
                        <td>${value.nama_tamu}</td>
                        <td>${value.no_telp_tamu}</td>
                        <td>${value.jenis_identitas}</td>
                        <td>${value.nomor_kendaraan}</td>
                    </tr>
                `);
                no++;
            });
            $('#vTable').show();
            if (data.length) {
                viewBtnPrint('show');
                viewBtnExportExcel('show');
                viewBtnExportPDF('show');
            }else{
                viewBtnPrint('hide');
                viewBtnExportExcel('hide');
                viewBtnExportPDF('hide');
            }
        }
    });
}

function viewBtnPrint(status='show') {
    <?php if ($showPrintPreview) : ?>
        btnPrint = $('#btnPrint');
        if (status=='show') {
            btnPrint.show();
        }else{
            btnPrint.hide();
        }
    <?php endif ?>
}

function viewBtnExportExcel(status='show') {
    <?php if ($showExportExcel) : ?>
        btnExportExcel = $('#btnExportExcel');
        if (status=='show') {
            btnExportExcel.show();
        }else{
            btnExportExcel.hide();
        }
    <?php endif ?>
}

function viewBtnExportPDF(status='show') {
    <?php if ($showExportPDF) : ?>
        btnExportPDF = $('#btnExportPDF');
        if (status=='show') {
            btnExportPDF.show();
        }else{
            btnExportPDF.hide();
        }
    <?php endif ?>
}
</script>
