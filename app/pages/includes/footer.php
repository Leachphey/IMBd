  <div class="container">
      <footer class="d-flex flex-wrap justify-content-between align-items-center py-3 my-4 border-top">
          <p class="col-md-4 mb-0 text-body-secondary">&copy; 2025 IMBd, Inc</p> <a href="/"
              class="col-md-4 d-flex align-items-center justify-content-center mb-3 mb-md-0 me-md-auto link-body-emphasis text-decoration-none"
              aria-label="Bootstrap"> <svg class="bi me-2" width="40" height="32" aria-hidden="true">
                  <use xlink:href="#bootstrap"></use>
              </svg> </a>
          <ul class="nav col-md-4 justify-content-end">
              <form action="<?= ROOT ?>/search" method="get">
                  <input type="hidden" name="find_top_movie" value="1">
                  <button type="submit" class="dropdown-item">Filmler</button>
              </form>
              <form action="<?= ROOT ?>/search" method="get">
                  <input type="hidden" name="top_series" value="1">
                  <button type="submit" class="dropdown-item">Diziler</button>
              </form>
              <form action="<?= ROOT ?>/home">
                  <button type="submit" class="dropdown-item">Ana sayfa
              </form>
          </ul>
      </footer>
  </div>



  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"
      integrity="sha384-7+zCNj/IqJ95wo16oMtfsKbZ9ccEh31eOz1HGyDuCQ6wgnyJNSYdrPa03rtR1zdB"
      crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"
      integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13"
      crossorigin="anonymous"></script>