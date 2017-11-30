# Upvid Link

## Example 1
```php
<?php
include 'upvid-link.php';

$upvid_link = new UpvidLink('YOUR_KEY', 'YOUR_CODE');
echo $upvid_link->link();
```

## Example 2
```php
<?php
include 'upvid-link.php';

# first link
$upvid_link = new UpvidLink('YOUR_KEY', 'YOUR_CODE');
echo $upvid_link->link();

# second link
$upvid_link->newCode('YOUR_CODE');
echo $upvid_link->link();
```
