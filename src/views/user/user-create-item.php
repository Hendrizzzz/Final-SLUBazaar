<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Listing - SLU Bazaar</title>
    <!-- We can reuse parts of the market CSS and add specific styles for the form -->
    <link rel="stylesheet" href="/assets/css/user/user-market.css"> 
    <link rel="stylesheet" href="/assets/css/user/user-create-item.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body>
    <div class="main-container">
        <!-- Reusable Sidebar -->
        <nav class="sidebar">
            <div class="sidebar-top">
                <a href="/profile" class="nav-link" title="Profile"><i class="fas fa-user"></i></a>
                <a href="/market" class="nav-link" title="Marketplace"><i class="fas fa-store"></i></a>
                <a href="/items/create" class="nav-link active" title="Sell Item"><i class="fas fa-plus-circle"></i></a>
                <a href="/messages" class="nav-link" title="Messages"><i class="fas fa-paper-plane"></i></a>
            </div>
            <div class="sidebar-bottom">
                 <a href="/logout" class="nav-link" title="Logout"><i class="fa-solid fa-right-from-bracket"></i></a>
            </div>
        </nav>

        <!-- Main Content Area -->
        <main class="content-wrapper">
            <div class="background-overlay"></div>
            <div class="content">
                <header class="page-header">
                    <h1>SLU Bazaar</h1>
                    <p>The official marketplace of Saint Louis University.</p>
                </header>
                
                <div class="listing-container">
                    <form action="/items/create" method="POST" enctype="multipart/form-data" class="listing-form">
                        <div class="form-header">
                            <h2>New Listing</h2>
                        </div>
                        <div class="form-body">
                            <div class="form-left">
                                <div class="form-group">
                                    <label for="title">Title:</label>
                                    <input type="text" id="title" name="title" required>
                                </div>
                                <div class="form-group">
                                    <label for="description">Description:</label>
                                    <textarea id="description" name="description" rows="6" required></textarea>
                                </div>
                                <div class="form-row">
                                    <div class="form-group">
                                        <label for="starting_bid">Starting Amount:</label>
                                        <input type="number" id="starting_bid" name="starting_bid" step="0.01" min="0" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="duration">Duration (in days):</label>
                                        <input type="number" id="duration" name="duration" min="1" max="14" value="7" required>
                                    </div>
                                </div>
                                 <div class="form-group">
                                    <label for="category">Category:</label>
                                    <select id="category" name="category" required>
                                        <option value="">Select a category</option>
                                        <option value="books">General</option>
                                        <option value="clothing">Books</option>
                                        <option value="electronics">Electronics</option>
                                        <option value="furniture">Toys</option>
                                        <option value="toys">Others</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-right">
                                <label>Insert Photos:</label>
                                <div class="photo-upload-grid">
                                    <div class="photo-upload-slot">
                                        <input type="file" id="photo1" name="photos[]" class="photo-input" accept="image/*">
                                        <label for="photo1" class="photo-label"><i class="fas fa-image"></i></label>
                                    </div>
                                    <div class="photo-upload-slot">
                                        <input type="file" id="photo2" name="photos[]" class="photo-input" accept="image/*">
                                        <label for="photo2" class="photo-label"><i class="fas fa-image"></i></label>
                                    </div>
                                    <div class="photo-upload-slot">
                                        <input type="file" id="photo3" name="photos[]" class="photo-input" accept="image/*">
                                        <label for="photo3" class="photo-label"><i class="fas fa-image"></i></label>
                                    </div>
                                    <div class="photo-upload-slot">
                                        <input type="file" id="photo4" name="photos[]" class="photo-input" accept="image/*">
                                        <label for="photo4" class="photo-label"><i class="fas fa-image"></i></label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-footer">
                            <button type="submit" class="submit-btn">Add Listing</button>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>

    <script src="/assets/js/user/user-create-item.js"></script>
</body>
</html>