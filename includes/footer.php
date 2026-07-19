<footer class="modern-footer">
  <div class="footer-content">
    <div class="footer-left">
      <p>&copy; <span id="year"></span> <a href="https://sterlinggroup.com.bd" target="_blank" rel="noopener noreferrer" class="brand-name">Sterling Group</a>. IT department all rights reserved.</p>
      <p class="developer-credit">Developed by <a href="tel:+8801751467162" target="_blank">Provat Sen</a></p>
    </div>
    
    <div class="social-links">
      <a href="https://facebook.com/provatkumarsen" target="_blank" aria-label="Facebook" class="social-icon fb">
        <i class="fab fa-facebook-f"></i>
      </a>
      <a href="https://instagram.com/provatsenbd" target="_blank" aria-label="Instagram" class="social-icon ig">
        <i class="fab fa-instagram"></i>
      </a>
      <a href="https://linkedin.com/in/provatsen" target="_blank" aria-label="LinkedIn" class="social-icon li">
        <i class="fab fa-linkedin-in"></i>
      </a>
      <a href="https://youtube.com/@provatsen" target="_blank" aria-label="YouTube" class="social-icon yt">
        <i class="fab fa-youtube"></i>
      </a>
      <a href="https://wa.me/+8801751467162" target="_blank" aria-label="WhatsApp" class="social-icon wa">
        <i class="fab fa-whatsapp"></i>
      </a>
    </div>
  </div>
</footer>

<!-- Dynamic Year Script -->
<script>
  document.getElementById('year').textContent = new Date().getFullYear();
</script>

<!-- Font Awesome (Using 6.x for consistency with header) -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
 <!-- Google Fonts - Roboto (Regular 400) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400&display=swap" rel="stylesheet">

<style>
  .modern-footer {
    width: 100%;
    background-color: #111827; /* Slightly darker for better contrast */
    color: #FFFFFF;
    font-family: 'Roboto', sans-serif; 
    border-top: 1px solid rgba(255, 255, 255, 0.05);
    margin-top: auto;
    /* Updated height control */
    min-height: 80px; /* Minimum height */
    padding: 0.5rem 0; /* Reduced vertical padding for tighter control */
    display: flex;
    align-items: center;
  }

  .footer-content {
    max-width: 1400px; /* Matched to standard dashboard widths */
    margin: 0 auto;
    padding: 0 1.5rem;
    display: flex;
    flex-direction: row;
    justify-content: space-between;
    align-items: center;
    gap: 1.5rem;
    width: 100%;
  }

  .footer-left {
    line-height: 1.6;
  }

  .footer-left p {
    margin: 0;
    font-size: 0.85rem;
  }

  .brand-name {
    color: #f3f4f6;
    font-weight: 600;
  }

  .developer-credit {
    font-size: 0.8rem !important;
    opacity: 100;
  }

  .footer-left a {
    color: #60a5fa;
    text-decoration: none;
    font-weight: 500;
    transition: color 0.2s ease;
  }

  .footer-left a:hover {
    color: #FFFFFF;
    text-decoration: underline;
  }

  .social-links {
    display: flex;
    gap: 12px;
    align-items: center;
  }

  .social-icon {
    color: #fff;
    background-color: #1f2937;
    width: 38px;
    height: 38px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 10px; /* Modern rounded square look */
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    text-decoration: none;
    font-size: 1.1rem;
    border: 1px solid rgba(255, 255, 255, 0.05);
  }

  /* Brand Specific Hover Colors */
  .social-icon.fb:hover { background-color: #1877F2; transform: translateY(-3px); }
  .social-icon.ig:hover { background: radial-gradient(circle at 30% 107%, #fdf497 0%, #fdf497 5%, #fd5949 45%,#d6249f 60%,#285AEB 90%); transform: translateY(-3px); }
  .social-icon.li:hover { background-color: #0077b5; transform: translateY(-3px); }
  .social-icon.yt:hover { background-color: #ff0000; transform: translateY(-3px); }
  .social-icon.wa:hover { background-color: #25D366; transform: translateY(-3px); }

  /* Mobile Responsiveness */
  @media (max-width: 640px) {
    .modern-footer {
      min-height: 120px; /* Taller for mobile to accommodate stacked layout */
      padding: 1rem 0; /* Adjusted padding for mobile */
    }

    .footer-content {
      flex-direction: column;
      text-align: center;
      gap: 1.2rem;
      padding: 0 1rem;
    }

    .social-links {
      justify-content: center;
      width: 100%;
    }

    .social-icon {
      width: 42px; /* Larger tap targets for mobile */
      height: 42px;
    }
  }

  /* Ensure footer stays at bottom if content is short */
  body {
    display: flex;
    flex-direction: column;
    min-height: 100vh;
  }
</style>