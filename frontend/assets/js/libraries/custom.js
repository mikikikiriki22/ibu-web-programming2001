$(document).ready(function () {
  // $("main#spapp > section").height($(document).height() - 60); // Izbacio jer mi onda main overlap-a sa footerom, nidje veze

  var app = $.spapp({ pageNotFound: "error_404" }); // initialize

  // define routes
  app.route({
    view: "login",
    onCreate: function () {},
    onReady: function () {
      localStorage.clear();
      $("body").removeClass("bg-light").addClass("bg-dark");
      $("footer").hide();
      $("#login_button").text("Log in")
    },
  });

  app.route({
    view: "homepage",
    onCreate: function () {},
    onReady: function () {
      $("body").removeClass("bg-dark").addClass("bg-light");
      $("footer").show();
      $("#login_button").text("Log out"); 
    },
  });
  app.route({
    view: "profile",
    onCreate: function () {},
    onReady: function () {
      $("body").removeClass("bg-dark").addClass("bg-light");
      $("footer").show();
    },
  });
  app.route({
    view: "admin",
    onCreate: function () {},
    onReady: function () {
      $("body").removeClass("bg-dark").addClass("bg-light");
      $("footer").show();
    },
  });

  // run app
  app.run();
});
