<?php
    namespace lib\dfile;

    class Di18n {
        public $allowedFileTypes;

        private function createRandomFileName($sourceFileName) {
            $u = explode(".",$sourceFileName);
            $suffix = $u[count($u) - 1];
            $newFileName = md5($sourceFileName) . date("Ymdhis") . "." . $suffix;
            return $newFileName;
        }

        private function getSuffix($fileName) {
            $bol = explode(".", $fileName);
            return $bol[count($bol) - 1];
        }

        private function isImageFile($fileName) {
            $u = explode(".",$fileName);
            $suffix = strtolower($u[count($u) - 1]);
            if($suffix == 'jpg' || $suffix == 'jpeg' || $suffix == 'png' || $suffix == 'gif' || $suffix == 'svg') {
                return true;		
            }
            return false;   
        }

        private function getContentType($suffix) {
            $suffix = strtolower($suffix);
            if(isEqual($suffix, "doc")) { return "application/msword"; }
            if(isEqual($suffix, "xls")) { return "application/vnd.ms-excel"; }
            if(isEqual($suffix, "docx")) { return "application/vnd.openxmlformats-officedocument.wordprocessingml.document"; }
            if(isEqual($suffix, "xlsx")) { return "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"; }
            if(isEqual($suffix, "jpg") || isEqual($suffix, "jpeg")) { return "image/jpeg"; }
            if(isEqual($suffix, "gif")) { return "image/gif"; }
            if(isEqual($suffix, "png")) { return "image/png"; }
            if(isEqual($suffix, "txt")) { return "text/txt"; }
            if(isEqual($suffix, "pdf")) { return "application/pdf"; }
            if(isEqual($suffix, "swf")) { return "application/octet-stream"; }
        }

        public function uploadFile($destinationFolder, $htmlFileObject, $fileName = "", $preffix = "") {
            $result = array(); // result[0]: sonuc , result[1]: aciklama
            if($htmlFileObject["name"] != '') {
                if($fileName == '')
                    $newFileName = $this->createRandomFileName($htmlFileObject['name']);
                else {
                    $suffix = $this->getSuffix($htmlFileObject['name']);
                    $newFileName = $fileName . "." . $suffix;
                }
            
                if($preffix) {
                    $newFileName = $preffix . "_" . $newFileName;
                }
                
                if(!$this->isAllowedFile($suffix)) {
                    $result[] = "ERR";
                    $result[] = "Belitilen dosyaya izin verilmiyor. Lütfen şu uzantılı dosya seçiniz: " . $this->allowedFileTypes;
                    return $result;
                }

                $fullUploadPath = $destinationFolder . "/" . $newFileName;
                $tmpFile = $htmlFileObject['tmp_name'];
                $upload = move_uploaded_file($tmpFile, $fullUploadPath);
        
                if($upload) {
                    $result[] = "OK";
                    $result[] = $newFileName;
                    return $result;
                }
            }
            $result[] = "ERR";
            $result[] = "Lütfen bir dosya seciniz...";
            return $result;
        }

        /*
        * 	verilen bir imaj dosyasinin istenen genislikte bir 
        * 	kopyasini olusturup kaydeder.
        * 	$savePath yeni dosyanin kaydedilecegi klasor ve yeni dosya adindan
        * 	olusmalidir. Orn : upload/images/12345.jpg
        * 	$sourceFile sunucuda varolan bir imaj dosyasi olmalidir. Sadece
        * 	gif, jpg ve png formatindaki dosyalar islenebilir.
        * 
        * 	$processWay : 	Imajin neye gore kucultulecegini belirtir.
        * 					w : yeni imaj genisligi $newSize degeri olur
        * 					h : yeni imaj yuksekligi $newSize degeri olur
        * 					b : imaj yatay ise genislik dikey ise yukseklik degeri $newSize olur
        */
        public function imageCreateFromSourceFile($sourceFile, $savePath, $newSize, $processWay = 'w') {
            $suffix = $this->getSuffix($sourceFile);
            list($sourceImgWidth, $sourceImgHeight) = getimagesize($sourceFile);
           
            if($processWay == 'b') {
                if($sourceImgWidth > $sourceImgHeight) {
                    $processWay = 'w';
                } else {
                    $processWay = 'h';
                }
            }
           
            // genislige gore kucult
            if($processWay == 'w') {
                if($sourceImgWidth < $newSize) { $newSize = $sourceImgWidth; }
                $rate = $sourceImgWidth / $sourceImgHeight;
                $newImgHeight = $newSize / $rate;
                $sample = imagecreatetruecolor($newSize,$newImgHeight);
            } else if($processWay == 'h') {
                if($sourceImgHeight < $newSize) { $newSize = $sourceImgHeight; }
                $rate = $sourceImgHeight / $sourceImgWidth;
                $newImgWidth = $newSize / $rate;
                $sample = imagecreatetruecolor($newImgWidth, $newSize);
            }
       
            switch($suffix) {
                case "gif":
                    $source = imagecreatefromgif($sourceFile);
                    break;
                case "jpg":
                    $source = imagecreatefromjpeg($sourceFile);
                    break;
                case "jpeg":
                    $source = imagecreatefromjpeg($sourceFile);
                    break;
                case "png":
                    $source = imagecreatefrompng($sourceFile);
                    break;
            }
           
           if($processWay == 'w') {
               imagecopyresampled($sample, $source, 0, 0, 0, 0, $newSize, $newImgHeight, $sourceImgWidth, $sourceImgHeight);
           } else {
               imagecopyresampled($sample, $source, 0, 0, 0, 0, $newImgWidth, $newSize, $sourceImgWidth, $sourceImgHeight);
           }
           imagejpeg($sample, $savePath, 100);
           imagedestroy($sample);
       }

       private function isAllowedFile($suffix) {
           if($this->allowedFileTypes != null) {
               $exp = explode(",", $this->allowedFileTypes);
               foreach($allow as $exp) {
                   if(strtolower(trim($allow)) == $suffix) return true;
               }
               return false;
           }
           // type kisitlamasi yok
           return true;
       }
    }
?>