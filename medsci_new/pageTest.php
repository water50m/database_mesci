<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<input 
    type="number" 
    class="form-control" 
    placeholder="รับ...คน" 
    aria-label="Text input" 
    id="countInput" 
    name="_count1" 
    readonly
    ondblclick="this.removeAttribute('readonly');" 
    onblur="this.setAttribute('readonly', true);" 
    style="cursor: pointer;"
>

</body>
</html>