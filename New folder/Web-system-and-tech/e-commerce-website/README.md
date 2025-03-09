# E-Commerce Website

Welcome to the E-Commerce Website project! This project is designed to provide a seamless online shopping experience, featuring a user-friendly interface and dynamic content.

## Project Structure

The project is organized as follows:

```
e-commerce-website
├── src
│   ├── assets
│   │   ├── css
│   │   │   └── styles.css       # CSS styles for the website
│   │   ├── js
│   │   │   └── scripts.js       # JavaScript for interactivity
│   │   └── images               # Directory for image assets
│   ├── components
│   │   ├── header.html          # Header component with navigation
│   │   ├── footer.html          # Footer component with copyright info
│   │   └── product-card.html    # Product card component
│   ├── pages
│   │   ├── index.html           # Main landing page
│   │   ├── product.html         # Product detail page
│   │   └── cart.html            # Shopping cart page
│   └── utils
│       └── helpers.js           # Utility functions
├── package.json                  # NPM configuration file
├── .gitignore                    # Files to ignore in version control
└── README.md                     # Project documentation
```

## Features

- **Responsive Design**: The website is designed to be fully responsive, ensuring a great experience on both desktop and mobile devices.
- **Dynamic Product Display**: Products are displayed using reusable components, making it easy to manage and update.
- **Shopping Cart Functionality**: Users can add products to their cart and proceed to checkout seamlessly.
- **Utility Functions**: Helper functions are provided to assist with common tasks, such as formatting prices.

## Setup Instructions

1. Clone the repository:
   ```
   git clone <repository-url>
   ```
2. Navigate to the project directory:
   ```
   cd e-commerce-website
   ```
3. Install the dependencies:
   ```
   npm install
   ```
4. Open the `index.html` file in your browser to view the website.

## Usage Guidelines

- Modify the CSS in `src/assets/css/styles.css` to change the appearance of the website.
- Update the JavaScript in `src/assets/js/scripts.js` to add or modify interactivity.
- Add new products by editing the `src/components/product-card.html` file and updating the relevant pages.

## Contributing

Contributions are welcome! Please submit a pull request or open an issue for any suggestions or improvements.

## License

This project is licensed under the MIT License. See the LICENSE file for details.