(function ($) {
  "use strict";

  $(window).on("elementor/frontend/init", function () {
    var webinarStart = function ($scope, $) {
      var $_this = $scope.find(".empire_timer");

      $_this.startTimer({
        onComplete: function (element) {},
      });
    };

    elementorFrontend.hooks.addAction(
      "frontend/element_ready/webinar-start.default",
      webinarStart
    );

    elementor.channels.editor.on("empire_addon:editor:fetch_data", () =>
      alert("This is where you do your stuff")
    );
  });
})(jQuery);
