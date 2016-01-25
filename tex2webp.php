<?php
include 'systemExecute.php';
// Convert tex expression to webp image.
// $tex  TeX expression.
// $webp Filename of the image that will be created.
// Return value:
// 0 Success.
// 1 TeX expression error.
// 2 TeX compiling timeout error.
function tex2webp($tex, $webp)
{
  if( strlen($tex) == 0 )
    return 1;
  // Generate TeX code
  $contents ='
  \documentclass[12pt]{article}
  \usepackage{geometry} 
  \pagenumbering{gobble} 
  \clearpage
  \geometry{a6paper,left=1px,top=1px} 
  \begin{document} 
     $\displaystyle '.$tex.' $
  \end{document}';
  
  // Fill TeX source file
  file_put_contents("doc.tex",$contents);
  $png = $webp.'.png';
  
  // Compile TeX file to dvi, convert dvi to png and png to webp
  $command = "latex doc.tex && dvipng doc.dvi -bg Transparent -D 700 -o $png && cwebp $png -o $webp";
  $result = systemExecute($command,3);
  if( $result == 2 )
    return 2;
  if( $result == 1 || !file_exists($webp) )
    return 1;

  // Remove temporary useless files
  unlink($png);
  unlink("doc.tex");
  unlink("doc.aux");
  unlink("doc.dvi");
  unlink("doc.log");
  
  // Success
  return 0;
}
?>
