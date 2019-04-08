(function ($) {

  //
  // Check the status site RSS feeds periodically and display an alert if necessary.
  //

  let $alertWrappers;
  let currentAlert;

  function errorHandler() {
    removeAlerts();
    if (currentAlert) {
      currentAlert = null;
    }
  }

  function successHandler(feed) {
    const latestAlert = getAlertData(feed);

    if (latestAlert && !userHidAlert(latestAlert.alertID)) {
      if (isNewAlert(latestAlert) || alertContentChanged(latestAlert)) {
        removeAlerts();
        insertAlerts(latestAlert);
        currentAlert = latestAlert;
      }
    } else {
      // No alert data was returned, so remove any existing alerts on the page
      removeAlerts();
      if (currentAlert) {
        currentAlert = null;
      }
    }
  }

  function isNewAlert(alertData) {
    if (currentAlert && currentAlert.alertID === alertData.alertID) {
      return false;
    }
    return true;
  }

  function alertContentChanged(alertData) {
    if (currentAlert && JSON.stringify(currentAlert) === JSON.stringify(alertData)) {
      return false;
    }
    return true;
  }

  //
  // Given a data set and a template, returns a jQuery object for
  // alert markup.
  //
  function createAlert(alertData, $alertTemplate) {
    const $alert      = $($alertTemplate.html());
    const $alertInner = $alert.hasClass('ucf-alert') ? $alert : $alert.find('.ucf-alert');
    const $alertLink  = $alert.find('.ucf-alert-content');
    const $title      = $alert.find('.ucf-alert-title');
    const $body       = $alert.find('.ucf-alert-body');
    const $cta        = $alert.find('.ucf-alert-cta');
    const $closeBtn   = $alert.find('.ucf-alert-close');

    if ($alertInner.length) {
      $alertInner
        .attr('data-alert-id', alertData.alertID)
        .addClass(`ucf-alert-type-${alertData.type}`);
    }

    if ($alertLink.length) {
      $alertLink.attr('href', alertData.url);
    }

    if ($title.length) {
      $title.text(alertData.title);
    }

    if ($body.length) {
      $body.text(alertData.description);
    }

    if ($cta.length) {
      $cta.text(alertData.cta);
    }

    if ($closeBtn.length) {
      $closeBtn.on('click', () => {
        hideAlert(alertData.alertID);
      });
    }

    return $alert;
  }

  //
  // Creates and inserts alert markup into each placeholder on the page.
  //
  function insertAlerts(alertData) {
    $alertWrappers.each(function () {
      const $alertWrapper = $(this);
      const id = $alertWrapper.attr('data-script-id');
      const $alertTemplate = $(`#${id}`);

      if (!$alertTemplate) {
        return false;
      }

      const $alert = createAlert(alertData, $alertTemplate);
      return $alertWrapper
        .empty()
        .append($alert);
    });

    $(document).trigger('ucfalert.added');
  }

  //
  // Returns the name for a cookie by its alert ID.
  //
  function getCookieName(alertID) {
    return `ucf_alert_display_${alertID}`;
  }

  //
  // Returns true/false for whether a cookie is set that signifies a
  // particular alert has been disabled by the user.
  //
  function userHidAlert(alertID) {
    if (Cookies.get(getCookieName(alertID)) === 'hide') {
      return true;
    }
    return false;
  }

  //
  // Hides all alerts on the page with the corresponding alert ID, and
  // sets a cookie to keep them hidden across pageviews.
  //
  function hideAlert(alertID) {
    $(`.ucf-alert[data-alert-id="${alertID}"]`).hide();
    Cookies.set(getCookieName(alertID), 'hide', {
      domain: UCFAlert.domain
    });
  }

  //
  // Parses raw feed data and returns an object of usable values.
  //
  function getAlertData(feed) {
    const $newest = $($(feed).find('item')[0]);
    if ($newest.length) {
      return {
        alertID: $newest.find('postID').text(),
        title: $newest.find('title').text(),
        description: $newest.find('description').text(),
        type: $newest.find('alertType').text(),
        url: $newest.find('link').text(),
        cta: $newest.find('cta').text()
      };
    }

    return false;

  }

  //
  // Removes all alerts from the page.
  //
  function removeAlerts() {
    const $alerts = $('.ucf-alert');
    if ($alerts.length) {
      $alerts.remove();
    }
    $(document).trigger('ucfalert.removed');
  }

  function fetchAlert() {
    return $.ajax({
      url: UCFAlert.url,
      cache: false,
      dataType: 'xml',
      success: successHandler,
      error: errorHandler
    });
  }

  function init() {
    $alertWrappers = $('.ucf-alert-wrapper');
    if ($alertWrappers.length) {
      fetchAlert();
      setInterval(fetchAlert, parseInt(UCFAlert.refreshInterval, 10));
    }
  }

  init();

}(jQuery));
