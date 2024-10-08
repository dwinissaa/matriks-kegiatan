$(document).ready(function () {
  // SELECT2
  $('select[name="tahun"]').select2({
    allowClear: true,
    placeholder: "Pilih Tahun",
    width: "100%",
  });
  $('select[name="bulan"]').select2({
    allowClear: true,
    placeholder: "Pilih Bulan",
    width: "100%",
  });
  $('select[name="tim"]').select2({
    allowClear: true,
    placeholder: "Pilih Tim",
    width: "100%",
  });
  $('select[name="kegiatan"]').select2({
    allowClear: true,
    placeholder: "Pilih Kegiatan",
    width: "100%",
  });
  $('select[name="anggota"]').select2({
    allowClear: true,
    placeholder: "Pilih Anggota",
    width: "100%",
  });
  $('select[name="satuan"]').select2({
    allowClear: false,
    dropdownParent: $("#pekerjaanModal"),
    placeholder: "Pilih Satuan",
    width: "100%",
  });

  // ELEMENT
  let tahun = $('select[name="tahun"]');
  let bulan = $('select[name="bulan"]');
  let tim = $('select[name="tim"]');
  let kegiatan = $('select[name="kegiatan"]');
  let anggota = $('select[name="anggota"]');
  tahun.val("00").trigger("change");
  bulan.val("00").trigger("change");
  tim.val("00").trigger("change");
  kegiatan.val("00").trigger("change");
  anggota.val("00").trigger("change");
  let t = tahun.val();
  let b = bulan.val();
  let ti = tim.val();
  let k = kegiatan.val();
  let a = anggota.val();

  // HIDE ELEMENT BUTTON
  $('select[name="anggota"]').on("change", function () {
    if ($('select[name="anggota"]').val()) {
      $("#add-pekerjaan").show();
      // ADD TEXT
      $('input[name="tahunStatic"]').attr(
        "value",
        $('select[name="tahun"]').find(":selected").val()
      );

      $('input[name="bulanStatic"]').attr(
        "value",
        bulan_arr[$('select[name="bulan"]').find(":selected").val() - 1]
      );

      $('input[name="kegiatanStatic"]').attr(
        "value",
        $('select[name="kegiatan"]').find(":selected").text()
      );
      $('input[name="kegiatanVal"]').attr(
        "value",
        $('select[name="kegiatan"]').find(":selected").val()
      );

      $('input[name="anggotaStatic"]').attr(
        "value",
        $('select[name="anggota"]').find(":selected").text()
      );
      $('input[name="anggotaVal"]').attr(
        "value",
        $('select[name="anggota"]').find(":selected").val()
      );
    } else {
      $("#add-pekerjaan").hide();
    }
  });

  $('select[name="satuan"]').on("change", function () {
    if ($('select[name="satuan"]').val() == "Lainnya") {
      $("#satuan_lain_container").show();
    } else {
      $("#satuan_lain_container").hide();
    }
  });

  // Parse URL to variable
  var getUrlParameter = function getUrlParameter(sParam) {
    var sPageURL = window.location.href,
      sURLVariables = sPageURL.split("/"),
      sParameterName,
      i;

    for (i = 0; i < sURLVariables.length; i++) {
      sParameterName = sURLVariables[i].split("=");

      if (sParameterName[0] === sParam) {
        return sParameterName[1] === undefined
          ? true
          : decodeURIComponent(sParameterName[1]);
      }
    }
    return false;
  };

  // INITIAL YEAR
  if (getUrlParameter("tahun")) {
    $('select[name="tahun"]').val(getUrlParameter("tahun")).trigger("change");
  }
  if (tahun.val()) {
    t = tahun.val();
  } else {
    t = "00";
  }
  $.ajax({
    url: "/filterPekerjaan/tahun=" + t,
    type: "GET",
    data: {
      _token: "{{ csrf_token() }}",
    },
    dataType: "json",
    success: function (data) {
      $('select[name="bulan"]').empty();
      $('select[name="bulan"]').append('<option value=""></option>');
      $.each(data, function (key, bulan, $bulan_arr) {
        $('select[name="bulan"]').append(
          "<option value=" +
            bulan.bulan +
            ">" +
            bulan_arr[bulan.bulan - 1] +
            "</option>"
        );
      });
      // SET BULAN
      if (getUrlParameter("bulan")) {
        $('select[name="bulan"]')
          .val(getUrlParameter("bulan"))
          .trigger("change");
      }
    },
  });

  // ONCHANGE YEAR
  $('select[name="tahun"]').on("change", function () {
    if (tahun.val()) {
      t = tahun.val();
      window.history.replaceState(
        {},
        "",
        "/view-pekerjaan/tahun=" +
          tahun.val() +
          "/bulan=" +
          getUrlParameter("bulan") +
          "/tim=" +
          getUrlParameter("tim") +
          "/kegiatan=" +
          getUrlParameter("kegiatan") +
          "/anggota=" +
          getUrlParameter("anggota")
      );
      $.ajax({
        url: "/filterPekerjaan/tahun=" + t,
        type: "GET",
        data: {
          _token: "{{ csrf_token() }}",
        },
        dataType: "json",
        success: function (data) {
          $('select[name="bulan"]').empty();
          $('select[name="bulan"]').append('<option value=""></option>');
          $.each(data, function (key, bulan, $bulan_arr) {
            $('select[name="bulan"]').append(
              "<option value=" +
                bulan.bulan +
                ">" +
                bulan_arr[bulan.bulan - 1] +
                "</option>"
            );
          });
          // SET BULAN
          if (getUrlParameter("bulan")) {
            console.log(getUrlParameter("tim"));
            $('select[name="bulan"]')
              .val(getUrlParameter("bulan"))
              .trigger("change");
          }
        },
      });
    } else {
      t = "00";
      $('select[name="bulan"]').empty();
      $('select[name="bulan"]').val("").trigger("change");
    }
  });

  // ONCHANGE BULAN
  $('select[name="bulan"]').on("change", function () {
    if (bulan.val()) {
      b = bulan.val();
      window.history.replaceState(
        {},
        "",
        "/view-pekerjaan/tahun=" +
          getUrlParameter("tahun") +
          "/bulan=" +
          b +
          "/tim=" +
          getUrlParameter("tim") +
          "/kegiatan=" +
          getUrlParameter("kegiatan") +
          "/anggota=" +
          getUrlParameter("anggota")
      );
      $.ajax({
        url: "/filterPekerjaan/tahun=" + t + "/bulan=" + b,
        type: "GET",
        data: {
          _token: "{{ csrf_token() }}",
        },
        dataType: "json",
        success: function (data) {
          console.log(data);
          $('select[name="tim"]').empty();
          $('select[name="tim"]').append('<option value=""></option>');
          $.each(data, function (key, tim) {
            $('select[name="tim"]').append(
              "<option value=" + tim.subject_meter + ">" + tim.tim + "</option>"
            );
          });
          // SET TIM
          if (getUrlParameter("tim")) {
            console.log(getUrlParameter("tim"));
            $('select[name="tim"]')
              .val(getUrlParameter("tim"))
              .trigger("change");
          }
        },
      });
    } else {
      b = "00";
      $('select[name="tim"]').empty();
      $('select[name="tim"]').val("").trigger("change");
    }
  });

  // FILTER TIM
  $('select[name="tim"]').on("change", function () {
    if (tim.val()) {
      ti = tim.val();
      window.history.replaceState(
        {},
        "",
        "/view-pekerjaan/tahun=" +
          getUrlParameter("tahun") +
          "/bulan=" +
          getUrlParameter("bulan") +
          "/tim=" +
          ti +
          "/kegiatan=" +
          getUrlParameter("kegiatan") +
          "/anggota=" +
          getUrlParameter("anggota")
      );
      $.ajax({
        url: "/filterPekerjaan/tahun=" + t + "/bulan=" + b + "/tim=" + ti,
        type: "GET",
        data: {
          _token: "{{ csrf_token() }}",
        },
        dataType: "json",
        success: function (data) {
          $('select[name= "kegiatan"]').empty();
          $('select[name= "kegiatan"]').append('<option value=""></option>');
          $.each(data, function (key, kegiatan) {
            $('select[name= "kegiatan"]').append(
              "<option value=" +
                kegiatan.id_keg +
                ">" +
                kegiatan.kegiatan +
                "</option>"
            );
          });
          // SET KEGIATAN
          if (getUrlParameter("kegiatan")) {
            $('select[name="kegiatan"]')
              .val(getUrlParameter("kegiatan"))
              .trigger("change");
          }
        },
      });
    } else {
      ti = "00";
      $('select[name="kegiatan"]').empty();
      $('select[name="kegiatan"]').val("").trigger("change");
    }
  });

  // FILTER KEGIATAN
  $('select[name="kegiatan"]').on("change", function () {
    if (kegiatan.val()) {
      k = kegiatan.val();
      window.history.replaceState(
        {},
        "",
        "/view-pekerjaan/tahun=" +
          getUrlParameter("tahun") +
          "/bulan=" +
          getUrlParameter("bulan") +
          "/tim=" +
          getUrlParameter("tim") +
          "/kegiatan=" +
          k +
          "/anggota=" +
          getUrlParameter("anggota")
      );
      $.ajax({
        url: "/filterPekerjaan/kegiatan=" + k,
        type: "GET",
        data: {
          _token: "{{ csrf_token() }}",
        },
        dataType: "json",
        success: function (data) {
          $('select[name="anggota"]').empty();
          $('select[name="anggota"]').append('<option value=""></option>');
          $.each(data, function (key, anggota) {
            $('select[name="anggota"]').append(
              "<option value=" +
                anggota.id_anggota +
                ">" +
                anggota.id_anggota +
                "_" +
                anggota.nama +
                "</option>"
            );
          });
          // SET ANGGOTA
          if (getUrlParameter("anggota")) {
            $('select[name="anggota"]')
              .val(getUrlParameter("anggota"))
              .trigger("change");
          }
        },
      });
    } else {
      k = "00";
      $('select[name="anggota"]').empty();
      $('select[name="anggota"]').val("").trigger("change");
    }
  });

  // FILTER TAHUN INIT

  var table = $("#pekerjaan-table").DataTable({
    order: [],
    scrollX: "100%",
    responsive: true,
    autowidth: true,
    processing: true,
    serverSide: true,
    ajax: {
      url: "view-pekerjaan",
      data: function (d) {
        d.tahun_filter = $('select[name="tahun"]').val();
        d.bulan_filter = $('select[name="bulan"]').val();
        d.tim_filter = $('select[name="tim"]').val();
        d.kegiatan_filter = $('select[name="kegiatan"]').val();
        d.anggota_filter = $('select[name="anggota"]').val();
        return d;
      },
    },
    columnDefs: [
      {
        targets: 0, // your case first column
        className: "text-center",
        width: "4%",
      },
    ],
    columns: [
      {
        data: "periode",
        name: "periode",
      },
      {
        data: "kegiatan",
        name: "kegiatan",
      },
      {
        data: "nama",
        name: "nama",
      },
      {
        data: "uraian_pekerjaan",
        name: "uraian_pekerjaan",
      },
      {
        data: "target",
        name: "target",
      },
      {
        data: "harga_satuan",
        name: "harga_satuan",
      },
      {
        data: "biaya",
        name: "biaya",
      },
      {
        data: "aksi",
        name: "aksi",
      },
    ],
    order: [
      // [5, 'desc']
    ],
  });
  $("[data-toggle=tooltip]").tooltip();

  // Filter
  $(".filter").on("change", function () {
    table.ajax.reload(null, false);
  });

  // SENDS PEKERJAAN
  $("#saveBtn").on("click", function (e) {
    $("#formAddPekerjaan").find(".is-invalid").removeClass("is-invalid");
    $("#formAddPekerjaan").find(".invalid-feedback").remove();

    $.ajax({
      type: "POST",
      url: "store-pekerjaan",
      data: {
        _token: "{{ csrf_token() }}",
        kegiatanVal: $("#kegiatanVal").first().attr("value"),
        anggotaVal: $("#anggotaVal").first().attr("value"),
        uraian_pekerjaan: $("#uraian_pekerjaan").val(),
        target: $("#target").first().val(),
        satuan: $("#satuan").first().val(),
        satuan_lain: $("#satuan_lain").first().val(),
        harga_satuan: $("#harga_satuanVal").first().attr("value"),
      },
      async: false,
      success: function (response, status) {
        console.log(response); //log
        $.cookie("cookie_pek", response); //set cookie
        Swal.fire({
          icon: "success",
          title: "Sukses",
          text: "Pekerjaan berhasil ditambahkan",
          showConfirmButton: false,
          timer: 1500,
        }).then(function () {
          window.location = "view-pekerjaan";
        });
      },
      error: function (XMLHttpRequest, textStatus, errorThrown) {
        const data = XMLHttpRequest.responseJSON;
        let html = "";
        if (data.errors) {
          console.log(data.errors);
          $.each(data.errors, function (key, value) {
            $("#" + key)
              .addClass("is-invalid")
              .after('<div class="invalid-feedback">' + value + "</div>");
          });
        } else if (data.message) {
          html = `<li>${data.message}</li>`;
          Swal.fire({
            icon: "error",
            title: "GALAT",
            html: "Gagal merekam penilaian Anda. ERR=" + `<ul>${html}</ul>`,
          });
        }
      },
    });
    // return false;
  });
  // $('form').find('.help-block').remove();
  // $('form').find('.form-group').removeClass('has-error');
});
// <------ Document ready ends here

// <------------ INPUT HARGA ------------>
function escapeRegExp(string) {
  return string.replace(/[.*+?^${}()|[\]\\]/g, "\\$&"); // $& means the whole matched string
}

function replaceAll(str, find, replace) {
  return str.replace(new RegExp(escapeRegExp(find), "g"), replace);
}

function stringtoFloat(price) {
  return parseFloat(replaceAll(replaceAll(price, ".", ""), ",", "."));
}

$("#target, #harga_satuan").on("input", function (event) {
  // Do magical things
  let angka = stringtoFloat($("#harga_satuan").val());
  $("#harga").val(function (index, value) {
    return (angka * parseInt($("#target").val())).toString().replace(".", ",");
  });

  // Save the real value
  $("#harga_satuanVal").attr("value", stringtoFloat($("#harga_satuan").val()));
  if ($("#harga_satuanVal").first().attr("value") == "NaN") {
    $("#harga_satuanVal").removeAttr("value");
  }
  $(".price").val(function (index, value) {
    return value
      .replace(/(?!\,)\D/g, "")
      .replace(/(?<=\,.*)\,/g, "")
      .replace(/(?<=\,\d\d).*/g, "")
      .replace(/\B(?=(\d{3})+(?!\d))/g, ".");
  });
});
