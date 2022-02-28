<?php


$d = file("input.txt");
$NoTag = '.';
$search = ['，', '。'];
$replace = "|";

$answerPartStart = '—';
$bS = '《';
$bE = '》';
$result = [];
$errorNum=0;
foreach ($d as $item) {
    //去除题目前面的编号
    $noPos = mb_strpos($item, $NoTag);
    $item1=$item;
    if($noPos !==false){
        $item1 = mb_substr($item, $noPos + 1);
    }
    $item1 = trim($item1);
    $itemArray = preg_split('/(?<!^)(?!$)/u', $item1 );

    $answerPartStartPos = mb_strpos($item1,$answerPartStart);

    if($answerPartStartPos==false){
        echo "error:","\t",$item,PHP_EOL;
        $errorNum++;
        continue;
    }
    while($itemArray[$answerPartStartPos+1]==$answerPartStart){
        $answerPartStartPos++;
    }

    $length = mb_strlen($item1);
    //如果出处是书籍
    $bSPos = mb_strpos($item1,$bS, $answerPartStartPos);
    if($bSPos !==false){
        $bEPos = mb_strpos($item1,$bE, $answerPartStartPos);
        $answer = mb_substr($item1, $bSPos+1,$bEPos-$bSPos-1);
        $question = mb_substr($item1,0,$bSPos).mb_substr($item1,$bEPos);
    }else{
        $explainS = mb_strpos($item1,'(',$answerPartStartPos);
        //对答案 有补充说明的情况
        if($explainS !==false){
            $explainE = mb_strpos($item1,')',$answerPartStartPos);
            //分答案在中间和答案在前面两种情况
            if($explainE !=($length-1)){
                $answer = mb_substr($item1,$explainE+1);
            }else{
                $answer = mb_substr($item1,$answerPartStartPos+1, $explainS-$answerPartStartPos-1);
            }
        }else{
            $answer = mb_substr($item1,$answerPartStartPos+1);
        }
    }
    $s = str_replace($answer, str_pad('',mb_strlen($answer),'_'),$item1);
    $result[] = trim($s) . "|||填空||| |||" . $answer . PHP_EOL;
}
shuffle($result);
foreach ($result as $i) {
    echo $i;
}