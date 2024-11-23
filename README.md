<h1>Custom Products API Plugin</h1>

<p>
  A WordPress plugin for managing WooCommerce products on a remote site using the WooCommerce REST API. 
  This plugin provides an intuitive admin interface for listing, creating, and configuring API settings.
</p>

<h2>Features</h2>
<ul>
  <li><strong>List Products:</strong> Fetch and display a list of products from a remote WooCommerce site in a tabular format.</li>
  <li><strong>Create Products:</strong> Add new products to the remote WooCommerce site via a user-friendly form.</li>
  <li><strong>Configuration Page:</strong> Configure the API Endpoint URL, Consumer Key, and Consumer Secret from the WordPress admin area.</li>
  <li><strong>Dynamic Styling:</strong> Clean, responsive, and visually appealing UI/UX for all pages.</li>
</ul>

<h2>Installation</h2>
<ol>
  <li><strong>Download the Plugin</strong>
    <pre>
git clone https://github.com/your-username/custom-products-api.git
    </pre>
  </li>
  <li><strong>Install the Plugin</strong>
    <ul>
      <li>Go to the WordPress admin dashboard.</li>
      <li>Navigate to <code>Plugins > Add New</code>.</li>
      <li>Click <strong>Upload Plugin</strong> and select the <code>.zip</code> file.</li>
      <li>Click <strong>Install Now</strong> and activate the plugin.</li>
    </ul>
  </li>
  <li><strong>Configure the Plugin</strong>
    <ul>
      <li>Navigate to <code>Custom Products API > Configuration</code> in the WordPress admin menu.</li>
      <li>Enter the following:
        <ul>
          <li><strong>API Endpoint URL:</strong> <code>https://stagingdeveloper.site/wp-json/wc/v3</code></li>
          <li><strong>Consumer Key:</strong> Your WooCommerce API consumer key.</li>
          <li><strong>Consumer Secret:</strong> Your WooCommerce API consumer secret.</li>
        </ul>
      </li>
      <li>Save the settings.</li>
    </ul>
  </li>
</ol>

<h2>Usage</h2>
<ol>
  <li><strong>List Products</strong>
    <p>Go to <code>Custom Products API > List Products</code> to fetch and view products from the remote WooCommerce site. Products are displayed in a responsive table with alternating row colors.</p>
  </li>
  <li><strong>Create Products</strong>
    <p>Go to <code>Custom Products API > Create Product</code> to add new products to the remote site. Fill out the form fields (e.g., Name, Description, Price, Stock Quantity) and click <strong>Create Product</strong>.</p>
  </li>
  <li><strong>Configuration</strong>
    <p>Update API settings under <code>Custom Products API > Configuration</code>.</p>
  </li>
</ol>

<h2>Technical Overview</h2>
<h3>Plugin Structure</h3>
<pre>
custom-products-api/
├── includes/
│   ├── class-create-product.php    # Logic for Create Product page
│   ├── class-list-products.php     # Logic for List Products page
│   ├── class-settings.php          # Logic for Configuration page
├── assets/
│   ├── style.css                   # Styles for UI/UX
│   ├── script.js                   # Scripts for dynamic behaviors
├── custom-products-api.php         # Main plugin file
├── README.md                       # Documentation
</pre>

<h3>Core Features</h3>
<ul>
  <li><strong>WordPress Standards:</strong>
    <ul>
      <li><code>add_menu_page()</code> and <code>add_submenu_page()</code> for admin menus.</li>
      <li><code>wp_remote_get()</code> and <code>wp_remote_post()</code> for API calls.</li>
      <li><code>sanitize_text_field()</code> and <code>sanitize_textarea_field()</code> for input validation.</li>
    </ul>
  </li>
  <li><strong>Error Handling:</strong> Displays success/error messages based on API response and logs errors for debugging.</li>
  <li><strong>Security:</strong> Uses WordPress nonces for form submissions and validates/sanitizes all inputs.</li>
  <li><strong>Styling and UX:</strong> Custom CSS ensures a clean, responsive, and modern UI. Alternating row colors improve readability.</li>
</ul>

<h2>Requirements</h2>
<ul>
  <li>WordPress 5.0 or higher</li>
  <li>WooCommerce 3.5 or higher</li>
  <li>PHP 7.4 or higher</li>
</ul>

<h2>Contributing</h2>
<p>We welcome contributions! To contribute:</p>
<ol>
  <li>Fork the repository.</li>
  <li>Create a new branch:
    <pre>
git checkout -b feature-name
    </pre>
  </li>
  <li>Commit your changes:
    <pre>
git commit -m "Add your message here"
    </pre>
  </li>
  <li>Push to your branch:
    <pre>
git push origin feature-name
    </pre>
  </li>
  <li>Create a pull request.</li>
</ol>

<h2>License</h2>
<p>This project is licensed under the MIT License. See the <code>LICENSE</code> file for details.</p>

<h2>Acknowledgments</h2>
<ul>
  <li><a href="https://developer.wordpress.org/plugins/" target="_blank">WordPress Plugin Handbook</a></li>
  <li><a href="https://woocommerce.github.io/woocommerce-rest-api-docs/" target="_blank">WooCommerce REST API Documentation</a></li>
  <li>Inspiration from the WordPress and WooCommerce developer communities.</li>
</ul>

<h2>Contact</h2>
<p>For questions or support, feel free to reach out:</p>
<ul>
  <li><strong>GitHub Issues:</strong> Submit an issue in the <a href="https://github.com/desoem/custom-products-api/issues" target="_blank">Issues Tab</a>.</li>
  <li><strong>Email:</strong>rsumantri.hotmail.co.id</li>
</ul>
