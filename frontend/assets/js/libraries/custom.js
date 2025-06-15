$(document).ready(function () {
  // $("main#spapp > section").height($(document).height() - 60); // Izbacio jer mi onda main overlap-a sa footerom, nidje veze

  var app = $.spapp({ pageNotFound: "error_404" }); // initialize

  // define routes
  app.route({
    view: "login",
    onCreate: function () { },
    onReady: function () {
      localStorage.clear();
      $("body").removeClass("bg-light").addClass("bg-dark");
      $("footer").hide();
      $("#login_button").text("Log in")
    },
  });

  app.route({
    view: "homepage",
    onCreate: function () { },
    onReady: function () {
      $("#item").html("");
      $("#homepage").css("display", "block");
      $("#profile").css("display", "none");
      $("#admin").css("display", "none");
      $("#register").css("display", "none");
      $("#adminpage").css("display", "none");
      $("body").removeClass("bg-dark").addClass("bg-light");
      $("footer").show();
      $("#login_button").text("Log out");
      // Add adminpage button if user is admin
      let user = null;
      try {
        user = JSON.parse(localStorage.getItem("user"));
      } catch (e) { }
      if (user && user.role === "admin") {
        if ($("#adminpage-btn").length === 0) {
          $(".navbar-nav").append('<li class="nav-item"><a id="adminpage-btn" class="nav-link" href="#adminpage">Admin Panel</a></li>');
        }
      } else {
        $("#adminpage-btn").remove();
      }

      // Dynamically load fragrances
      RestClient.get("parfumes", function (fragrances) {
        let cardsHtml = "";
        // Always clear the container before rendering
        $(".row.gx-4.gx-lg-5.row-cols-2.row-cols-md-3.row-cols-xl-4.justify-content-center").html("");
        // Only render fragrances from the database (assume all returned are valid)
        fragrances.forEach(function (frag) {
          cardsHtml += `
            <div class="col mb-5">
              <a href="#item?id=${frag.id}" class="text-decoration-none d-block fragrance-card" data-id="${frag.id}">
                <div class="card position-relative overflow-hidden" style="height: 400px;">
                  <img class="card-img h-100 w-100" src="${frag.image_url || 'assets/images/default.jpg'}" alt="Fragrance" style="object-fit: scale-down; display: block; border-radius: 10px;">
                  <div class="card-img-overlay d-flex flex-column align-items-center justify-content-end p-3">
                    <h5 class="fw-bolder mb-2" style="color: #8C6A5D;">${frag.name}</h5>
                    <p class="mb-0" style="color: #3E3232;"><span>${frag.brand_name || ''}</span></p>
                  </div>
                </div>
              </a>
            </div>
          `;
        });
        $(".row.gx-4.gx-lg-5.row-cols-2.row-cols-md-3.row-cols-xl-4.justify-content-center").html(cardsHtml);
        // Remove duplicate admin panel button if present
        $("#adminpage-btn").remove();
        if (user && user.role === "admin") {
          if ($("#adminpage-btn").length === 0) {
            $(".navbar-nav").append('<li class="nav-item"><a id="adminpage-btn" class="nav-link" href="#adminpage">Admin Panel</a></li>');
          }
        }
      });
    },
  });

  app.route({
    view: "profile",
    onCreate: function () { },
    onReady: function () {
      // Always show profile, hide others, and clear content at the start
      $("#profile").css("display", "block").html('<div class="text-center py-5">Loading profile...</div>');
      $("#homepage, #admin, #register, #adminpage, #item").css("display", "none");
      let user = null;
      try {
        user = JSON.parse(localStorage.getItem("user"));
      } catch (e) { }
      if (!user || !user.id) {
        $("#profile").html('<div class="alert alert-danger text-center">You must be logged in to view your profile.</div>');
        // Double guarantee
        $("#profile").css("display", "block");
        $("#homepage, #admin, #register, #adminpage, #item").css("display", "none");
        return;
      }
      RestClient.get(`users/${user.id}`, function (profile) {
        $("#profile").html(`
          <div class="container px-4 px-lg-5 my-5">
            <div class="row gx-4 gx-lg-5 align-items-center">
              <div class="col-md-5 text-center">
                <div class="mb-4">
                  <div style="width: 180px; height: 180px; margin: 0 auto; border-radius: 50%; background: #f5f5f5; border: 2px solid #8C6A5D; display: flex; align-items: center; justify-content: center; overflow: hidden;">
                    ${profile.image_url ? `<img src="${profile.image_url}" alt="Profile Picture" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;" />` : `<svg width='80' height='80' fill='#8C6A5D' viewBox='0 0 16 16'><path d='M10 5a2 2 0 1 1-4 0 2 2 0 0 1 4 0z'/><path fill-rule='evenodd' d='M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8zm8-7a7 7 0 0 0-5.468 11.37c.69-1.19 2.065-2.37 5.468-2.37 3.403 0 4.778 1.18 5.468 2.37A7 7 0 0 0 8 1z'/></svg>`}
                  </div>
                </div>
                <h2 class="fw-bolder mb-1" style="color: #8C6A5D;">${profile.username}</h2>
                <div class="fs-5 mb-3">
                  <span class="text-secondary">${profile.email}</span>
                </div>
              </div>
              <div class="col-md-1"></div>
              <div class="col-md-6">
                <h4 class="fw-bold mb-3" style="color: #8C6A5D;">Profile Description</h4>
                <p class="lead">${profile.about ? profile.about : '<span class="text-muted">No description provided.</span>'}</p>
                <button class="btn btn-outline-primary mt-4" id="edit-profile-btn">Edit Profile</button>
              </div>
            </div>
          </div>
        `);

        // Add edit profile modal
        $("body").append(`
          <div class="modal fade" id="editProfileModal" tabindex="-1" aria-labelledby="editProfileModalLabel" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="editProfileModalLabel">Edit Profile</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="edit-profile-form">
                  <div class="modal-body">
                    <div class="mb-3">
                      <label for="edit-username" class="form-label">Username</label>
                      <input type="text" class="form-control" id="edit-username" value="${profile.username}" required>
                    </div>
                    <div class="mb-3">
                      <label for="edit-email" class="form-label">Email</label>
                      <input type="email" class="form-control" id="edit-email" value="${profile.email}" required>
                    </div>
                    <div class="mb-3">
                      <label for="edit-about" class="form-label">About</label>
                      <textarea class="form-control" id="edit-about" rows="3">${profile.about ? profile.about : ''}</textarea>
                    </div>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                  </div>
                </form>
              </div>
            </div>
          </div>
        `);
        $(document).off('click', '#edit-profile-btn').on('click', '#edit-profile-btn', function () {
          const modal = new bootstrap.Modal(document.getElementById('editProfileModal'));
          modal.show();
        });
        $(document).off('submit', '#edit-profile-form').on('submit', '#edit-profile-form', function (e) {
          e.preventDefault();
          const updatedData = {
            username: $('#edit-username').val(),
            email: $('#edit-email').val(),
            about: $('#edit-about').val()
          };
          RestClient.put(`users/${user.id}`, updatedData, function () {
            $('#editProfileModal').modal('hide');
            location.reload();
          }, function (xhr) {
            alert('Failed to update profile: ' + (xhr.responseJSON?.message || 'Unknown error'));
          });
        });

        // After profile info, show user's reviews
        $("#profile").append(`
          <div class="container px-4 px-lg-5 my-5" id="user-reviews-section">
            <div class="row gx-4 gx-lg-5 align-items-center mt-5">
              <div class="col-12">
                <h3 class="fw-bold text-uppercase mb-4" style="color: #8C6A5D">My Reviews</h3>
                <div id="user-reviews-list"><p class='text-center'>Loading your reviews...</p></div>
              </div>
            </div>
          </div>
        `);
        RestClient.get(`reviews/user/${user.id}`, function (reviews) {
          let reviewsHtml = '';
          if (reviews && reviews.length > 0) {
            reviews.forEach(function (review) {
              reviewsHtml += `
                <div class="card mb-3" data-review-id="${review.id}">
                  <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                      <h5 class="card-title">${review.fragrance_name || 'Fragrance'}</h5>
                      <p class="card-text">${review.comment || ''}</p>
                      <p class="card-text"><small class="text-muted">Rating: ${review.rating || 'N/A'}</small></p>
                    </div>
                    <button class="btn btn-outline-danger btn-sm delete-review-btn" data-review-id="${review.id}">Delete</button>
                  </div>
                </div>
              `;
            });
          } else {
            reviewsHtml = '<p class="text-center">You have not left any reviews yet.</p>';
          }
          $("#user-reviews-list").html(reviewsHtml);
        }, function () {
          $("#user-reviews-list").html('<p class="text-center text-danger">Failed to load your reviews.</p>');
        });

        // Handle review delete
        $(document).off('click', '.delete-review-btn').on('click', '.delete-review-btn', function () {
          const reviewId = $(this).data('review-id');
          if (confirm('Are you sure you want to delete this review?')) {
            RestClient.delete(`reviews/${reviewId}`, function () {
              $(`.card[data-review-id='${reviewId}']`).remove();
            }, function (xhr) {
              alert('Failed to delete review: ' + (xhr.responseJSON?.message || 'Unknown error'));
            });
          }
        });

        // Double guarantee at the end
        $("#profile").css("display", "block");
        $("#homepage, #admin, #register, #adminpage, #item").css("display", "none");
      }, function () {
        $("#profile").html('<div class="alert alert-danger text-center">Failed to load profile data.</div>');
        // Double guarantee
        $("#profile").css("display", "block");
        $("#homepage, #admin, #register, #adminpage, #item").css("display", "none");
      });
    },
  });

  app.route({
    view: "adminpage",
    onCreate: function () { },
    onReady: function () {
      $("#item").html("");
      $("#adminpage").css("display", "block");
      $("#homepage, #profile, #register, #admin").css("display", "none");
      $("body").removeClass("bg-dark").addClass("bg-light");
      $("footer").show();

      // USERS CRUD
      function loadAdminUsers() {
        RestClient.get("users", function (users) {
          let usersHtml = users.map(user => `
            <tr data-user-id="${user.id}">
              <th scope="row">${user.id}</th>
              <td>${user.username}</td>
              <td>${user.email}</td>
              <td>
                ${user.image_url ? `<img src='${user.image_url}' alt='User Image' style='width:40px;height:40px;border-radius:50%;object-fit:cover;' />` : `<svg width='32' height='32' fill='#8C6A5D' viewBox='0 0 16 16'><path d='M10 5a2 2 0 1 1-4 0 2 2 0 0 1 4 0z'/><path fill-rule='evenodd' d='M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8zm8-7a7 7 0 0 0-5.468 11.37c.69-1.19 2.065-2.37 5.468-2.37 3.403 0 4.778 1.18 5.468 2.37A7 7 0 0 0 8 1z'/></svg>`}
              </td>
              <td>
                <button class="btn btn-primary view-user-btn">View</button>
                <button class="btn btn-danger delete-user-btn">Delete</button>
              </td>
            </tr>
          `).join("");
          $("#admin-users-table tbody").html(usersHtml);
        });
      }
      loadAdminUsers();
      // View user details in modal
      $(document).off('click', '.view-user-btn').on('click', '.view-user-btn', function () {
        const row = $(this).closest('tr');
        const userId = row.data('user-id');
        RestClient.get(`users/${userId}`, function (user) {
          $("body").append(`
            <div class="modal fade" id="viewUserModal" tabindex="-1" aria-labelledby="viewUserModalLabel" aria-hidden="true">
              <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="viewUserModalLabel">User Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body">
                    <p><strong>ID:</strong> ${user.id}</p>
                    <p><strong>Username:</strong> ${user.username}</p>
                    <p><strong>Email:</strong> ${user.email}</p>
                    <p><strong>Role:</strong> ${user.role}</p>
                    <p><strong>About:</strong> ${user.about || ''}</p>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                  </div>
                </div>
              </div>
            </div>
          `);
          const modal = new bootstrap.Modal(document.getElementById('viewUserModal'));
          modal.show();
          $('#viewUserModal').on('hidden.bs.modal', function () { $(this).remove(); });
        });
      });
      // Delete user
      $(document).off('click', '.delete-user-btn').on('click', '.delete-user-btn', function () {
        const row = $(this).closest('tr');
        const userId = row.data('user-id');
        if (confirm('Are you sure you want to delete this user?')) {
          RestClient.delete(`users/${userId}`, function () {
            row.remove();
          }, function (xhr) {
            alert('Failed to delete user: ' + (xhr.responseJSON?.message || 'Unknown error'));
          });
        }
      });

      // FRAGRANCES CRUD
      function loadFragrances() {
        RestClient.get("parfumes", function (fragrances) {
          let fragHtml = fragrances.map(frag => `
            <tr data-frag-id="${frag.id}">
              <th scope="row">${frag.id}</th>
              <td>${frag.brand_name}</td>
              <td>${frag.name}</td>
              <td>${frag.notes || ''}</td>
              <td>${frag.category || 'EDP'}</td>
              <td>-</td>
              <td>${frag.image_url || ''}</td>
              <td class="text-nowrap">
                <div class="d-flex gap-1">
                  <button class="btn btn-primary edit-frag-btn" data-bs-toggle="modal" data-bs-target="#addFragranceModal">Edit</button>
                  <button class="btn btn-danger delete-frag-btn">Delete</button>
                </div>
              </td>
            </tr>
          `).join("");
          $("#admin-frag-table tbody").html(fragHtml);
        });
      }
      loadFragrances();
      $(document).off('click', '.delete-frag-btn').on('click', '.delete-frag-btn', function () {
        const row = $(this).closest('tr');
        const fragId = row.data('frag-id');
        if (confirm('Are you sure you want to delete this fragrance?')) {
          RestClient.delete(`parfumes/${fragId}`, function () {
            row.remove();
          }, function (xhr) {
            alert('Failed to delete fragrance: ' + (xhr.responseJSON?.message || 'Unknown error'));
          });
        }
      });
      // Add fragrance
      $(document).off('click', '[data-bs-target="#addFragranceModal"]:not(.edit-frag-btn)').on('click', '[data-bs-target="#addFragranceModal"]:not(.edit-frag-btn)', function () {
        $('#addFragranceModalLabel').text('Add New Fragrance');
        $('#addFragranceModal form')[0].reset();
        $('#addFragranceModal .btn-success').text('Add Fragrance').data('edit-id', '');
      });
      // Edit fragrance
      $(document).off('click', '.edit-frag-btn').on('click', '.edit-frag-btn', function () {
        const row = $(this).closest('tr');
        const fragId = row.data('frag-id');
        RestClient.get(`parfumes/${fragId}`, function (frag) {
          $('#addFragranceModalLabel').text('Edit Fragrance');
          $('#fragranceName').val(frag.name);
          $('#brandName').val(frag.brand_name);
          $('#description').val(frag.description);
          $('#notes').val(frag.notes);
          $('#seasons').val(frag.seasons ? frag.seasons.split(', ') : []);
          $('#addFragranceModal .btn-success').text('Save Changes').data('edit-id', fragId);
        });
      });
      // Submit add/edit fragrance
      $(document).off('click', '#addFragranceModal .btn-success').on('click', '#addFragranceModal .btn-success', function (e) {
        e.preventDefault();
        const fragId = $(this).data('edit-id');
        const data = {
          name: $('#fragranceName').val(),
          brand_name: $('#brandName').val(),
          description: $('#description').val(),
          notes: $('#notes').val(),
          seasons: $('#seasons').val() ? $('#seasons').val().join(', ') : ''
        };
        if (fragId) {
          RestClient.put(`parfumes/${fragId}`, data, function () {
            $('#addFragranceModal').modal('hide');
            loadFragrances();
          }, function (xhr) {
            alert('Failed to update fragrance: ' + (xhr.responseJSON?.message || 'Unknown error'));
          });
        } else {
          RestClient.post('parfumes', data, function () {
            $('#addFragranceModal').modal('hide');
            loadFragrances();
          }, function (xhr) {
            alert('Failed to add fragrance: ' + (xhr.responseJSON?.message || 'Unknown error'));
          });
        }
      });

      // REVIEWS CRUD
      function loadReviews() {
        RestClient.get("reviews", function (reviews) {
          let reviewsHtml = reviews.map(review => `
            <tr data-review-id="${review.id}">
              <td>${review.reviewer_name || ''}</td>
              <td>${review.fragrance_name || ''}</td>
              <td>${review.comment || ''}</td>
              <td>${review.rating || ''}</td>
              <td><button class="btn btn-danger delete-review-btn">Delete</button></td>
            </tr>
          `).join("");
          $("#admin-reviews-table tbody").html(reviewsHtml);
        });
      }
      loadReviews();
      $(document).off('click', '.delete-review-btn').on('click', '.delete-review-btn', function () {
        const row = $(this).closest('tr');
        const reviewId = row.data('review-id');
        if (confirm('Are you sure you want to delete this review?')) {
          RestClient.delete(`reviews/${reviewId}`, function () {
            row.remove();
          }, function (xhr) {
            alert('Failed to delete review: ' + (xhr.responseJSON?.message || 'Unknown error'));
          });
        }
      });
    },
  });

  app.route({
    view: "register",
    onCreate: function () { },
    onReady: function () {
      $("#item").html("");
      $("#register").css("display", "block");
      $("#homepage").css("display", "none");
      $("#profile").css("display", "none");
      $("#admin").css("display", "none");
      $("#adminpage").css("display", "none");
      $("body").removeClass("bg-light").addClass("bg-dark");
      $("footer").hide();
      if (typeof AuthService !== 'undefined' && AuthService.initRegister) {
        AuthService.initRegister();
      }
    },
  });

  app.route({
    view: "item",
    onCreate: function () { },
    onReady: function () {
      $("#item").css("display", "block");
      $("#homepage").css("display", "none");
      $("#profile").css("display", "none");
      $("#admin").css("display", "none");
      $("#register").css("display", "none");
      $("#adminpage").css("display", "none");
      console.log("Item route triggered");
      const itemSection = document.getElementById("item");
      if (!itemSection) {
        alert("#item section not found in DOM!");
        return;
      }
      $("#item").html('<div class="alert alert-info">Loading fragrance details...</div>');
      const params = new URLSearchParams(window.location.hash.split('?')[1]);
      const id = params.get('id');
      if (!id) {
        $("#item").html('<div class="alert alert-danger">No fragrance selected.</div>');
        return;
      }
      RestClient.get(`parfumes/${id}`, function (frag) {
        console.log("Fragrance loaded:", frag);
        if (!frag || !frag.name) {
          $("#item").html('<div class="alert alert-danger">Fragrance not found or error loading data.</div>');
          return;
        }
        $("#item").html(`
          <div class="container py-5">
            <div class="row gx-4 gx-lg-5 align-items-center">
              <div class="col-md-5">
                <img class="card-img-top mb-5 mb-md-0 w-100" src="${frag.image_url || 'assets/images/default.jpg'}" alt="Fragrance" style="object-fit: scale-down; border-radius: 10px; max-height: 400px;" />
                <div class="container py-4">
                  <div class="row text-center mb-4">
                    <div class="col">
                      <h5 class="fw-bold text-uppercase" style="color: #8C6A5D">Best for Seasons</h5>
                      <p class="fs-5 text-secondary">${frag.seasons || 'N/A'}</p>
                    </div>
                  </div>
                  <div class="row text-center">
                    <div class="col">
                      <h5 class="fw-bold text-uppercase" style="color: #8C6A5D">Key Notes</h5>
                      <p class="fs-5 text-secondary">${frag.notes || 'N/A'}</p>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-md-1"></div>
              <div class="col-md-6">
                <h1 class="display-5 fw-bolder" style="color: #8C6A5D">${frag.name}</h1>
                <div class="fs-5 mb-4">
                  <span style="color: #3E3232; font-size: 1.2rem;">${frag.brand_name || ''}</span>
                </div>
                <p class="lead">${frag.description || ''}</p>
              </div>
            </div>
            <div class="row mt-5">
              <div class="col-12">
                <h3 class="fw-bold text-uppercase" style="color: #8C6A5D">Reviews</h3>
                <div id="reviews-list" class="mt-3"></div>
                <div id="review-form-wrapper" class="mt-4"></div>
              </div>
            </div>
          </div>
        `);

        // Fetch reviews for this fragrance
        RestClient.get(`reviews/fragrance/${id}`, function (reviews) {
          let reviewsHtml = "";
          if (reviews && reviews.length > 0) {
            reviews.forEach(function (review) {
              reviewsHtml += `
                <div class=\"card mb-3\">
                  <div class=\"card-body\">
                    <h5 class=\"card-title\">${review.reviewer_name || 'Anonymous'}</h5>
                    <p class=\"card-text\">${review.comment || ''}</p>
                    <p class=\"card-text\"><small class=\"text-muted\">Rating: ${review.rating || 'N/A'}</small></p>
                  </div>
                </div>
              `;
            });
          } else {
            reviewsHtml = '<p class=\"text-center\">No reviews yet.</p>';
          }
          $("#reviews-list").html(reviewsHtml);
        }, function () {
          $("#reviews-list").html('<p class="text-center text-danger">Failed to load reviews.</p>');
        });

        // Add review form below reviews, in the same col
        let reviewFormHtml = '';
        let user = null;
        try {
          user = JSON.parse(localStorage.getItem("user"));
        } catch (e) { }
        if (user && user.id) {
          reviewFormHtml = `
            <div class="card shadow-sm border-0">
              <div class="card-body p-4">
                <h4 class="card-title mb-3 fw-bold" style="color: #8C6A5D;">Leave a Review</h4>
                <form id="review-form">
                  <div class="mb-3">
                    <label for="review-rating" class="form-label">Rating</label>
                    <select class="form-select" id="review-rating" required>
                      <option value="">Select rating</option>
                      <option value="5">5 - Excellent</option>
                      <option value="4">4 - Very Good</option>
                      <option value="3">3 - Good</option>
                      <option value="2">2 - Fair</option>
                      <option value="1">1 - Poor</option>
                    </select>
                  </div>
                  <div class="mb-3">
                    <label for="review-comment" class="form-label">Comment</label>
                    <textarea class="form-control" id="review-comment" rows="3" maxlength="500" required placeholder="Share your experience..."></textarea>
                  </div>
                  <div class="d-grid">
                    <button type="submit" class="btn btn-success btn-lg">Submit Review</button>
                  </div>
                </form>
              </div>
            </div>
          `;
        } else {
          reviewFormHtml = '<div class="alert alert-info">Please log in to leave a review.</div>';
        }
        $("#review-form-wrapper").html(reviewFormHtml);

        // Handle review form submission
        $(document).off('submit', '#review-form').on('submit', '#review-form', function (e) {
          e.preventDefault();
          const rating = $("#review-rating").val();
          const comment = $("#review-comment").val();
          if (!rating || !comment) {
            alert('Please provide both a rating and a comment.');
            return;
          }
          const reviewData = {
            parfume_id: id,
            rating: rating,
            comment: comment
          };
          RestClient.post('reviews', reviewData, function () {
            // Reload reviews after successful submission
            RestClient.get(`reviews/fragrance/${id}`, function (reviews) {
              let reviewsHtml = "";
              if (reviews && reviews.length > 0) {
                reviews.forEach(function (review) {
                  reviewsHtml += `
                    <div class=\"card mb-3\">
                      <div class=\"card-body\">
                        <h5 class=\"card-title\">${review.reviewer_name || 'Anonymous'}</h5>
                        <p class=\"card-text\">${review.comment || ''}</p>
                        <p class=\"card-text\"><small class=\"text-muted\">Rating: ${review.rating || 'N/A'}</small></p>
                      </div>
                    </div>
                  `;
                });
              } else {
                reviewsHtml = '<p class=\"text-center\">No reviews yet.</p>';
              }
              $("#reviews-list").html(reviewsHtml);
            });
            // Clear form
            $("#review-form")[0].reset();
          }, function (xhr) {
            alert('Failed to submit review: ' + (xhr.responseJSON?.message || 'Unknown error'));
          });
        });
      }, function () {
        $("#item").html('<div class="alert alert-danger">Failed to load fragrance data from server.</div>');
      });
    },
  });

  // run app
  app.run();
});
