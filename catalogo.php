<html>

<head>

  <link rel="stylesheet" type="text/css" href="molekinho.css">

</head>
<body>
  <?php
  // Create connection
  $con=mysqli_connect("localhost","root","","molekinho");

  // Check connection
  if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
  }

  $query = "SELECT * from categoria";
  $categorias = mysqli_query($con, $query);
  $category = array();
  while ($cate = mysqli_fetch_assoc($categorias)){
    $category[$cate["id"]] = $cate["descricao"];
  }
  
  $query = "SELECT * from produto ORDER BY categoria, nome;";
  $products = mysqli_query($con, $query);
  $categoria_atual=0;
  $pixels_height;
  while ($prod = mysqli_fetch_assoc($products))
  {
    if ($prod['categoria'] != $categoria_atual){
      $categoria_atual = $prod['categoria'];
      show_category_header($category[$categoria_atual], $pixels_height);
    }
  
    display_product($prod, $pixels_height);
    
  }

  mysqli_close($con);

  function show_category_header($nome_categoria, &$height){
    if ($height <= 742){
      echo "<h1 class='category'><p>".$nome_categoria."</p></h1>";
      $height += 30;
    }
    else{
      echo "<h1 class='category pagebreakable'><p>".$nome_categoria."</p></h1>";
      $height = 30;
    }
    
  }
  
  function display_product($produto, &$height){
    $last_field_class = "product-details";
    
    //950 é o maximo que a altura da pagina pode atingir, 178 é a atual altura da div de produto, ou seja se tiver mais avançado que 772, quebra a linha
    if ($height< 773){
      $html = '<div class="product-line" id="product_'.$produto["id"].'">';
      $height += 178;
    }
    else{
      $html = '<div class="product-line pagebreakable" id="product_'.$produto["id"].'">';
      $height = 178;
    }
    
    $image_reized = 'phpThumb/phpThumb.php?src=../images/'.$produto["id"].'.jpg&h=133';
    
    //div 1: nome + foto
      //$html .= '<div class="product-main"><p><img src="images/'.$produto["id"].'.jpg"/><br/>'.$produto["nome"].'</p></div>';
      $html .= '<div class="product-main"><p><img src="'.$image_reized.'"/><br/>'.$produto["nome"].'</p></div>';
    
    //div2: quantidade(opcional, se houver)
    //if(!empty($produto["quantidade"])){
      $html .= '<div class="product-qtd"><p>'.$produto["quantidade"].'</p></div>';  
      //se esse foi usado, limita o tamanho do proximo
      $last_field_class = "product-short-details";
    //}
      
    //div3: detalhes, sabores(opcional, se houver)
    if(!empty($produto["detalhes"])){
      $html .= '<div class="'.$last_field_class.'"><p>'.$produto["detalhes"].'</p></div>';  
    }
    
    
    $html.='</div>';
    
    echo $html;
    
  }
  
  
  ?>
</body>
</html>
