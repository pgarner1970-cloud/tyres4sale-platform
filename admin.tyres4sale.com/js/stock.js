$(document).ready(function () {
  $("#wrapper").toggleClass("toggled");

  // -----------------------------
  // Persist last tyre size entry (admin)
  // -----------------------------
  var STORAGE_KEY = "ltc_admin_last_tyre_size_v1";

  function loadSavedTyreSize() {
    try {
      var raw = window.localStorage.getItem(STORAGE_KEY);
      if (!raw) return null;
      return String(raw);
    } catch (e) {
      return null;
    }
  }

  function saveTyreSize(val) {
    try {
      window.localStorage.setItem(STORAGE_KEY, String(val || ""));
    } catch (e) {}
  }

  var savedTyreSize = loadSavedTyreSize();
  if (savedTyreSize) {
    $("#tyresize").val(savedTyreSize);
  }

  // debounce typing so we don't hammer storage
  var _tyreSizeSaveTimer = null;
  $(document).on("input blur", "#tyresize", function () {
    var v = $(this).val();
    clearTimeout(_tyreSizeSaveTimer);
    _tyreSizeSaveTimer = setTimeout(function () {
      saveTyreSize(v);
    }, 150);
  });

  // -----------------------------
  // Manufacturer -> banding lookup used for "band:" filtering
  // -----------------------------
  var manufacturerBandingMap = {};

  // Prevent auto-submit while manufacturer list is still being populated
  var manufacturersReady = false;

  // Robust option match (handles whitespace in option values)
  function findOptionValueByTrimmedMatch($select, target) {
    var wanted = String(target).trim();
    var found = null;

    $select.find("option").each(function () {
      var v = String($(this).val()).trim();
      if (v === wanted) {
        found = $(this).val();
        return false;
      }
    });

    return found;
  }

  function applySelectValue($select, value) {
    if (!$select || !$select.length) return;

    if ($select.find("option[value='" + value + "']").length) {
      $select.val(value);
      return;
    }

    var match = findOptionValueByTrimmedMatch($select, value);
    if (match !== null) $select.val(match);
  }

  // -----------------------------
  // Tyre size quick input parsing
  // Examples: 1656515, 2255519 (digits only)
  // -----------------------------
  function parseTyreSize(raw) {
    if (!raw) return null;
    var s = String(raw).replace(/\s+/g, "").replace(/[^\d]/g, "");
    if (s.length !== 7) return null; // expecting 3+2+2 digits

    return {
      width: s.substr(0, 3),
      profile: s.substr(3, 2),
      rim: s.substr(5, 2)
    };
  }

  function applyTyreSizeToSelectsFromInput() {
    var parsed = parseTyreSize($("#tyresize").val());
    if (!parsed) return false;

    applySelectValue($("#width"), parsed.width);
    applySelectValue($("#profile"), parsed.profile);
    applySelectValue($("#rim"), parsed.rim);

    return true;
  }

  // -----------------------------
  // Brand clear button
  // -----------------------------
  function toggleClearBrandButton() {
    var $btn = $("#clearBrand");
    if (!$btn.length) return;

    var v = $("#manufacturer").val();
    if (v && v !== "*") {
      $btn.removeClass("d-none");
    } else {
      $btn.addClass("d-none");
    }
  }

  $(document).on("click", "#clearBrand", function (e) {
    e.preventDefault();
    applySelectValue($("#manufacturer"), "*");
    toggleClearBrandButton();

    // trigger change will now auto-submit (once manufacturersReady is true)
    $("#manufacturer").trigger("change");
  });

  // ✅ UPDATED: manufacturer change now auto-submits the form
  $(document).on("change", "#manufacturer", function () {
    toggleClearBrandButton();

    // Don’t auto-submit while the dropdown is being populated on page load
    if (!manufacturersReady) return;

    // Auto-submit the search form
    $("#search").trigger("submit");
  });

  // Apply size parsing as user types
  var sizeTypeTimer = null;
  $(document).on("input", "#tyresize", function () {
    clearTimeout(sizeTypeTimer);
    sizeTypeTimer = setTimeout(function () {
      applyTyreSizeToSelectsFromInput();
    }, 150);
  });

  $(document).on("blur", "#tyresize", function () {
    applyTyreSizeToSelectsFromInput();
  });

  // -----------------------------
  // Populate dropdowns (AJAX)
  // After each list loads, apply typed size
  // -----------------------------
  $.ajax({
    url: "functions/list_width.php",
    type: "post",
    success: function (response) {
      var len = response.length;
      $("#width").empty();
      for (var i = 0; i < len; i++) {
        var widthDesc = response[i]["widthDesc"];
        $("#width").append("<option value='" + widthDesc + "'>" + widthDesc + "</option>");
      }
      applyTyreSizeToSelectsFromInput();
    }
  });

  $.ajax({
    url: "functions/list_profile.php",
    type: "post",
    success: function (response) {
      var len = response.length;
      $("#profile").empty();
      for (var i = 0; i < len; i++) {
        var profileDesc = response[i]["profileDesc"];
        $("#profile").append("<option value='" + profileDesc + "'>" + profileDesc + "</option>");
      }
      applyTyreSizeToSelectsFromInput();
    }
  });

  $.ajax({
    url: "functions/list_rim.php",
    type: "post",
    success: function (response) {
      var len = response.length;
      $("#rim").empty();
      for (var i = 0; i < len; i++) {
        var rimDesc = response[i]["rimDesc"];
        $("#rim").append("<option value='" + rimDesc + "'>" + rimDesc + "</option>");
      }
      applyTyreSizeToSelectsFromInput();
    }
  });

  $.ajax({
    url: "functions/list_speed.php",
    type: "post",
    success: function (response) {
      var len = response.length;
      $("#speed").empty();
      $("#speed").append("<option value='*'>ALL</option>");
      for (var i = 0; i < len; i++) {
        var speedDesc = response[i]["speedDesc"];
        $("#speed").append("<option value='" + speedDesc + "'>" + speedDesc + "</option>");
      }
    }
  });

  $.ajax({
    url: "functions/list_manufacturers.php",
    type: "post",
    success: function (response) {
      var len = response.length;

      $("#manufacturer").empty();

      // Top items
      $("#manufacturer").append("<option value='*'>** ALL **</option>");
      $("#manufacturer").append("<option value='band:Budget'>Budget</option>");
      $("#manufacturer").append("<option value='band:Mid-range'>Mid-range</option>");
      $("#manufacturer").append("<option value='band:Premium'>Premium</option>");
      $("#manufacturer").append("<option disabled>──────────</option>");

      manufacturerBandingMap = {};

      for (var i = 0; i < len; i++) {
        var m = response[i]["Manufacturer"];
        var b = response[i]["banding"] || "Budget";
        manufacturerBandingMap[String(m || "").toUpperCase()] = b;

        $("#manufacturer").append("<option value='" + m + "'>" + m + "</option>");
      }

      toggleClearBrandButton();

      // ✅ allow auto-submit on brand change from now on
      manufacturersReady = true;
    }
  });

  // -----------------------------
  // Search submit -> call API -> render results
  // -----------------------------
  $("#search").submit(function (event) {
    event.preventDefault();

    // If user typed size, try apply it before search
    applyTyreSizeToSelectsFromInput();
    // Persist the raw input for next visit
    saveTyreSize($("#tyresize").val());
    toggleClearBrandButton();

    $("#resultsDiv").empty();

    var formData = {
      t: "enquiry",
      w: $("#width option:selected").val(),
      p: $("#profile option:selected").val(),
      r: $("#rim option:selected").val(),
      s: $("#speed option:selected").val(),
      f: $("#fuel option:selected").val(),
      wg: $("#wetgrip option:selected").val()
    };

    $.ajax({
      url: "https://tyres4sale.com/_api/apiSearch.php",
      type: "get",
      data: formData,
      dataType: "json"
    })
      .done(function (data) {
        var len = data.length;
        var rowsAdded = 0;

        var resultsHtml = "<table cellspacing=2 cellpadding=2 class='table table-striped'>";
        resultsHtml += "<thead><tr bgcolor=#cecece>" +
          "<td>EAN</td><td>Manufacturer</td><td>Description</td><td>Class</td><td>Supplier</td>" +
          "<td>Fuel</td><td>Wet</td><td>Noise</td><td>Buy</td><td>Sell (inc VAT)</td>" +
          "</tr></thead>";

        var selectedBrand = $("#manufacturer").val();

        for (var i = 0; i < len; i++) {
          // Brand filter handling:
          // * = no filter
          // band:Budget/Mid-range/Premium = band filter
          // otherwise = exact manufacturer match
          if (selectedBrand !== "*") {
            if (String(selectedBrand).indexOf("band:") === 0) {
              var selectedBand = String(selectedBrand).split("band:")[1];
              var mName = String(data[i]["Manufacturer"] || "").toUpperCase();
              var mBand = manufacturerBandingMap[mName] || "Budget";
              if (mBand !== selectedBand) continue;
            } else {
              if (data[i]["Manufacturer"] != selectedBrand) continue;
            }
          }

          rowsAdded++;

          resultsHtml += "<tr>";
          resultsHtml += "<td>" + data[i]["EAN"] + "</td>";
          resultsHtml += "<td>" + data[i]["Manufacturer"] + "</td>";
          resultsHtml += "<td>" + data[i]["TyreDesc"] + "</td>";
          resultsHtml += "<td>" + data[i]["LTCClass"] + "</td>";
          resultsHtml += "<td>" + data[i]["Supplier"] + "</td>";
          resultsHtml += "<td>" + data[i]["RollingRes"] + "</td>";
          resultsHtml += "<td>" + data[i]["WetGrip"] + "</td>";
          resultsHtml += "<td>" + data[i]["NoisePerf"] + "</td>";
          resultsHtml += "<td>" + data[i]["UnitBuyPrice"] + "</td>";
          resultsHtml += "<td>" + data[i]["UnitPrice"] + "</td>";
          resultsHtml += "</tr>";
        }

        resultsHtml += "</table>";

        if (rowsAdded === 0) {
          $("#resultsDiv").html(
            "<div class='alert alert-warning mt-3'>" +
              "<strong>No results found.</strong><br>" +
              "Try adjusting the tyre size or filters." +
            "</div>"
          );
        } else {
          $("#resultsDiv").html(resultsHtml);
        }
      })
      .fail(function () {
        $("#resultsDiv").html(
          "<div class='alert alert-danger mt-3'>" +
            "<strong>Search failed.</strong><br>" +
            "Please try again in a moment." +
          "</div>"
        );
      });
  });

  // Keep the tyre size box in sync when dropdowns are used
  $(document).on("change", "#width, #profile, #rim", function () {
    var w = String($("#width").val() || "").trim();
    var p = String($("#profile").val() || "").trim();
    var r = String($("#rim").val() || "").trim();

    // only build if all are digits
    if (/^\d{3}$/.test(w) && /^\d{2}$/.test(p) && /^\d{2}$/.test(r)) {
      var combined = w + p + r;
      $("#tyresize").val(combined);
      saveTyreSize(combined);
    }
  });

});
