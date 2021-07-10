<?php
$update = false;

$errors = "";
$db = mysqli_connect('localhost', 'root', '', 'todo');
//insert value into database
$todo = mysqli_query($db, "SELECT * FROM todo");
if (isset($_POST['submit'])) {
  $task = $_POST['task'];
  if (empty($task)) {
    $errors = "You are required to fill in the task";
  } else {
    mysqli_query($db, "INSERT INTO todo (task) 
  VALUES('$task')");
    header('location: ./index.php');
  }

}

//make sure this block of code is outside your isset($_POST["submit]) block
if (isset($_GET['del_task'])) {
  $id = $_GET['del_task'];
  //add backticks to your SQL
  mysqli_query($db, "DELETE FROM `todo` WHERE `id`=$id");
  header('location: ./index.php');
}


if (isset($_GET['id'])) {
	$id = ($_GET['id']);
  if ($_GET["update"]) {
    $update = true;
    $todo = mysqli_query($db, "SELECT * FROM `todo` WHERE `id`=$id");
    if (mysqli_num_rows($todo) > 0) {
    	$task = mysqli_fetch_assoc($todo);
    }
  }
}

if(isset($_POST['update'])){
  	$task = ($_POST['task']);
  	$id = ($_POST['id']);
  	if (empty($task)) {
  		header("location: ./index.php?id=$id&error=Name is required");
  	}else {
         $sql = 
         mysqli_query($db,"UPDATE `todo` SET `task`='$task' WHERE `id`=$id");
         if ($todo) {
         	  header("location: ./index.php?success=successfully updated");
         }else {
            header("location: ./index.php?id=$id&error=unknown error occurred&$user_data");
         }
  	}
  }
?>

<!DOCTYPE html>
<HTML>

<head>
  <title>To-Do List</title>
  <link rel="stylesheet" href="style.css">
</head>

<body>

  <div class="main">
    <h1>To do:</h1>
    <hr>
  </div>
  <table class="table">
    <tbody>
      <?php $i = 1;
      while ($row = mysqli_fetch_array($todo)) { ?>
        <tr class="tr">
          <td><?php echo $i; ?></td>
          <td><?php echo $row['task']; ?></td>
          <td class="delete">
            <!-- <form action="<?php htmlspecialchars($_SERVER["PHP_SELF"]) ?>" method="POST">
              <input type="hidden" name="id" value="<?php echo $row['id'] ?>">
              <button type="submit" style="border: none; background: none;">DELETE</button>
            </form> -->
            
            <a href="index.php?del_task=<?php echo $row['id'] ?>">DELETE</a>
            <a href="index.php?id=<?php echo $row['id'] ?>&update=true">UPDATE</a>
          </td>
        </tr>
      <?php $i++;
      } ?>

    </tbody>
  </table>


  <?php

  if (!$update) { ?>
      <form action="index.php" method="Post">
        <?php if (isset($errors)) { ?>
          <p style="color: red;"><?php echo $errors; ?></p>
        <?php } ?>


        <label for="Task">Task </label>
        <input type="text" name="task" placeholder="What do you need to do?"><br><br>
        <button type="submit" name="submit">Save Item <span>&plus;</span></button>
      </form>
  <?php }else{ ?>
      <form action="index.php" method="Post">
        <?php if (isset($errors)) { ?>
          <p style="color: red;"><?php echo $errors; ?></p>
        <?php } ?>
        <input type="hidden" name="id" value="<?php echo $task["id"] ?>">
        <input type="hidden" name="update" value="true">

        <label for="Task">Task </label>
        <input type="text" name="task" value="<?php echo $task["task"] ?>"><br><br>
        <button type="submit" name="update">Save Item <span>&plus;</span></button>
      </form>
  <?php 

  }
  ?>





</body>

</HTML>