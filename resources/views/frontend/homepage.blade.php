<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AprilJoy</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link href="{{ asset('assets/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('assets/css/styles.css') }}"> <!-- custom styles will go here -->
</head>
<body>
    <!-- Header -->
    <header class="header">
        <nav class="top-header" style = "background-color: ">
            <div class="container mt-0" style = "padding-right: 0">
              <div class="row w-100">
                <div class="col-md-3">
                    <p class="text-white mt-2 mb-0"><i class="fas fa-map-marker-alt"></i> 123 Main St, Anytown, Philippines</p>
                </div>
                <div class="col-md-2">
                    <p class="text-white mt-2 mb-0"><i class="fas fa-phone"></i> +63 2 123 4567</p>
                </div>
                <div class="col-md-3">
                    <p class="text-white mt-2 mb-0"><i class="fas fa-envelope"></i> <a href="mailto:info@furnihome.com" class="text-white">info@furnihome.com</a></p>
                </div>
                <div class="col-md-4 ">
                    <div class="d-flex justify-content-end ">
                    <button class="btn btn-outline-success" type="submit"><i class="fab fa-facebook-f"></i></button>
                    <button class="btn btn-outline-success" type="submit"><i class="fab fa-instagram"></i></button>
                    </div>
                </div>
              </div>
            </div>
          </nav>
        <nav class="navbar navbar-expand-lg navbar-light top-nav">
        <div class="container">
            <a class="navbar-brand" href="#">
                {{-- <img src="{{ asset('assets/img/logo.png') }}" alt="" class ="home-logo" style = "width: 50px;">  --}}
                JENNYGRACE
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
            {{-- <form class="d-flex mx-auto">
                <input class="form-control me-2" type="search" placeholder="Search for Furnitures or Home Decors" aria-label="Search">
                <button class="btn btn-outline-success" type="submit">Search</button>
            </form> --}}
            <div class="ms-auto mb-2 mb-lg-0">
                <form class="d-flex">
                    <button class="btn btn-outline-success" type="submit"><i class="fas fa-envelope"></i> Let's Get in Touch</button>
                </form>
            </div>
            </div>
        </div>
        </nav>
        <nav class="navbar navbar-expand-lg navbar-light main-nav">
            <div class="container">
              <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                  <a class="nav-link" href="#" style="font-weight: bold; text-transform: uppercase; color: #333;">HOME</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="#" style="font-weight: bold; text-transform: uppercase; color: #333;">ABOUT US</a>
                </li>
                <li class="nav-item dropdown">
                  <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false" style="font-weight: bold; text-transform: uppercase; color: #333;">CATEGORIES</a>
                  <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                    <li><a class="dropdown-item" href="#">Living Room</a></li>
                    <li><a class="dropdown-item" href="#">Dining Room</a></li>
                    <li><a class="dropdown-item" href="#">Bedroom</a></li>
                  </ul>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="#" style="font-weight: bold; text-transform: uppercase; color: #333;">CONTACT US</a>
                </li>
              </ul>
            </div>
          </nav>
    </header>

    <!-- Main Content -->

    <main class="main-content">
        <div class="banner">
            <div class="row">
                <div class="col-md-12">
                    <img src="{{ asset('assets/img/homepagebanner2.png') }}" alt="Banner Image" class="img-fluid w-100">
                    <div class="banner-text">
                        <h1 class="text-white banner-custom-text">Furniture Solutions. Your Innovative Partner.</h1>
                        <p class="text-white banner-custom-text">Wide Selection of Furniture and Furnishing for Offices,<br>Schools, Resto-Bar, Hotels and Home Environment.</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="container mt-5">
            <h1>Furniture Store Products</h1>
            <p>We are dedicated to the production of quality products and service, while maintaining fast lead time.</p>
            <div class="row">
                <div class="col-md-4">
                    <div class="card mb-3 home-cards">
                        <div class="card-img-container">
                            <img src="{{ asset('assets/img/EXPLORER-L-SHAPE.webp') }}" class="card-img-top" alt="...">
                            <div class="card-img-overlay text-center">
                                <button>Shop</button>
                            </div>
                        </div>
                        <div class="card-body">
                            <h6 class="card-title"><span class="text-main">SALA SETS</h6>
                            <h6 class="card-title"><i class="fas fa-bars    "></i></span> 34 ITEMS</h6>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card mb-3 home-cards">
                        <div class="card-img-container">
                            <img src="{{ asset('assets/img/BMC-12.webp') }}" class="card-img-top" alt="...">
                            <div class="card-img-overlay text-center">
                                <button>Shop</button>
                            </div>
                        </div>
                        <div class="card-body">
                            <h6 class="card-title"><span class="text-main">CABINETS</h6>
                            <h6 class="card-title"><i class="fas fa-bars    "></i></span> 34 ITEMS</h6>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card mb-3 home-cards">
                        <div class="card-img-container">
                            <img src="{{ asset('assets/img/AMITY-SAM-BAR.webp') }}" class="card-img-top" alt="...">
                            <div class="card-img-overlay text-center">
                                <button>Shop</button>
                            </div>
                        </div>
                        <div class="card-body">
                            <h6 class="card-title"><span class="text-main">CHAIRS</h6>
                            <h6 class="card-title"><i class="fas fa-bars    "></i></span> 34 ITEMS</h6>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-3">
                <!-- Sidebar -->
                <aside class="sidebar">
                    <h2 class="sidebar-title">Categories</h2>
                    <ul class="list-group">
                    <li class="list-group-item">
                        <a href="#">Living Room</a>
                    </li>
                    <li class="list-group-item">
                        <a href="#">Dining Room</a>
                    </li>
                    <li class="list-group-item">
                        <a href="#">Bedroom</a>
                    </li>
                    <li class="list-group-item">
                        <a href="#">Home Office</a>
                    </li>
                    <li class="list-group-item">
                        <a href="#">Outdoor</a>
                    </li>
                    </ul>
                    <h2 class="sidebar-title">Brands</h2>
                    <ul class="list-group">
                    <li class="list-group-item">
                        <a href="#">Brand 1</a>
                    </li>
                    <li class="list-group-item">
                        <a href="#">Brand 2</a>
                    </li>
                    <li class="list-group-item">
                        <a href="#">Brand 3</a>
                    </li>
                    </ul>
                </aside>
                </div>
                <div class="col-lg-9">
                <!-- Products -->
                <div class="products">
                    <div class="row">
                    <div class="col-lg-4">
                        <div class="product-card">
                        <img src="https://via.placeholder.com/300x200" alt="Product Image">
                        <h3>Product 1</h3>
                        <p> Lorem ipsum dolor sit amet, consectetur adipiscing elit. </p>
                        <button class="btn btn-primary">View Details</button>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="product-card">
                        <img src="https://via.placeholder.com/300x200" alt="Product Image">
                        <h3>Product 2</h3>
                        <p> Lorem ipsum dolor sit amet, consectetur adipiscing elit. </p>
                        <button class="btn btn-primary">View Details</button>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="product-card">
                        <img src="https://via.placeholder.com/300x200" alt="Product Image">
                        <h3>Product 3</h3>
                        <p> Lorem ipsum dolor sit amet, consectetur adipiscing elit. </p>
                        <button class="btn btn-primary">View Details</button>
                        </div>
                    </div>
                    </div>
                </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
        <div class="row">
            <div class="col-lg-3">
            <!-- About Us -->
            <h2 class="footer-title">About Us</h2>
            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed sit amet nulla auctor, vestibulum magna sed, convallis ex.</p>
            </div>
            <div class="col-lg-3">
            <!-- Contact Us -->
            <h2 class="footer-title">Contact Us</h2>
            <ul class="list-unstyled">
                <li><i class="fas fa-map-marker-alt"></i> 123 Main St, Anytown, USA</li>
                <li><i class="fas fa-phone"></i> (123) 456-7890</li>
                <li><i class="fas fa-envelope"></i> <a href="mailto:info@furnihome.com">info@furnihome.com</a></li>
            </ul>
            </div>
            <div class="col-lg-3">
            <!-- Social Media -->
            <h2 class="footer-title">Follow Us</h2>
            <ul class="list-unstyled">
                <li><a href="#" target="_blank"><i class="fab fa-facebook-f"></i></a></li>
                <li><a href="#" target="_blank"><i class="fab fa-instagram"></i></a></li>
                <li><a href="#" target="_blank"><i class="fab fa-twitter"></i></a></li>
            </ul>
            </div>
            <div class="col-lg-3">
            <!-- Newsletter -->
            <h2 class="footer-title">Newsletter</h2>
            <p>Stay updated with our latest news and promotions!</p>
            <form>
                <input type="email" placeholder="Enter your email address">
                <button class="btn btn-primary">Subscribe</button>
            </form>
            </div>
        </div>
        <hr>
        <p class="text-center">&copy; 2023 FurniHome. All rights reserved.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
