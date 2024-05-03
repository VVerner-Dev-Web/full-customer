<div class="full-templates-admin-body">
  <div class="templately-wrapper">
    <div class="templately-header">
      <div class="templately-logo">
        <img src="<?= fullGetImageUrl('logo-novo.png') ?>" alt="Logo FULL">
      </div>
    </div>

    <div class="templately-container templately-pages-container">
      <div class="templately-container-row" id="endpoint-viewport">
        <div class="templately-contents">

          <div class="templately-contents-header">
            <div class="templately-contents-header-inner">
              <div class="templately-header-title">
                <h3>Integrações FULL.</h3>
                <input type="search" id="filter-products" placeholder="Procurar...">
              </div>
            </div>
          </div>

          <div class="full-page-content">
            <p>As integrações FULL. proporciona uma fusão perfeita entre nossa plataforma e os recursos PRO avançados do Wordpress. Dessa forma os plugins mais amados e desejados se integram perfeitamente à plataforma FULL. Essa parceria inovadora simplifica desde de o processo de instalação, ativação até a manutenção contínua visando a prevenção de falhas e correções técnicas.</p>
            <p>Com recursos inteligentes e abrangentes capacitamos empresas a explorarem o Worpdress de forma descomplicada, intuitiva em busa de resultados excepcionais</p>

            <div id="full-products-grid">
              <!-- JS -->
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div id="purchase-options" class="mfp-hide">
  <div class="modal-top-cover bg-full text-center">
    <figure class="position-absolute right-0 bottom-0 left-0" style="margin-bottom: -1px;">
      <svg preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" viewBox="0 0 1920 100.1">
        <path fill="#fff" d="M0,0c0,0,934.4,93.4,1920,0v100.1H0L0,0z"></path>
      </svg>
    </figure>
  </div>
  <div class="modal-top-cover-icon">
    <span class="icon icon-lg icon-light icon-circle icon-centered shadow-soft">
      <i class="tio-shopping-icon"></i>
    </span>
  </div>

  <div class="body">
    <h2>Opções de compra</h2>

    <ul class="list-group mb-0" id="purchase-options-list">

    </ul>
  </div>
</div>

<script id="purchase-option-card" type="text/template">
  <div class="card" data-item="{id}">
    <div class="card-body text-center">
      <img class="avatar" src="{thumbnailUrl}" alt="{name}">
      <h3>{name}</h3>
      <span class="badge">{typeLegend}</span>
    </div>
    <div class="card-footer">
      <div>
        <span class="font-size-sm">{purchaseOptionsLegend}</span>
      </div>
      <div>
        <a href="#!" class="open-purchase-options {purchase}">
          <i class="tio-shopping-icon list-group-icon"></i>
          Comprar licença
        </a>
      </div>
    </div>
  </div>
</script>

<script id="purchase-option-item-list" type="text/template">
  <li class="list-group-item">
    <a href="{url}" target="_blank" rel="noopener noreferrer">
      <div>
        <h5 class="my-0">{name}</h5>
        <p>{price}</p>
      </div>
      <div class="col-auto">
        <span class="btn btn-sm btn-full"> Comprar </span>
      </div>
    </a>
  </li>
</script>