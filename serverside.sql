-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 18, 2024 at 12:14 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `serverside`
--

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `comment` varchar(1000) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`id`, `post_id`, `comment`) VALUES
(22, 38, 'Would like to try!'),
(23, 42, 'Is this spicy?'),
(25, 48, 'This is a great post! I really enjoyed reading it.'),
(26, 49, 'Very informative, thank you for sharing.');

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `id` int(11) NOT NULL,
  `title` varchar(200) NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `content` varchar(2000) NOT NULL,
  `vegetarian_id` int(11) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`id`, `title`, `date`, `content`, `vegetarian_id`, `image`) VALUES
(19, 'Deviled Egg Salad', '2024-08-16 03:59:53', 'This lazy Deviled Egg Salad takes a classic appetizer and turns it into a quick lunch! It has the same ingredients but no piping or filling. Simply mix it all together and serve it with a spoon, on toast or a rice cake.Deviled Eggs are a pretty appetizer when entertaining. However, if I am enjoying it as lunch or a high protein snack, I don’t want to spend time piping the eggs. So, I take the same ingredients and mix them together to create a fun twist on egg salad! You can hard boil your eggs however you wish, but I love using my air fryer using this air fryer hard boiled eggs recipe!', 1, '1723780793-Lazy-Deviled-Egg-Salad-13.jpg'),
(22, 'Protein Bagels with Cottage Cheese', '2024-08-16 04:05:33', 'Whip up a batch of these quick and easy Protein Bagels with Cottage Cheese for breakfast or lunch in less than 30 minutes – they come out perfect!Remember my Greek yogurt bagels? They’re so last year! What’s new? Cottage cheese everything and anything! Cottage cheese is trending this year, and I’ve been using it in everything from my cottage cheese scrambled eggs, cottage cheese lasagna roll ups to savory cottage cheese bowls. These protein bagels are made with just 5 ingredients, plus toppings! You can make them in the oven or air fryer (my favorite way!). I basically swapped the yogurt in my bagel recipe for cottage cheese and it’s worked out beautifully. For extra protein, serve this with egg salad, chicken salad or turn it into a bacon egg and cheese breakfast bagel!', 1, '1723781133-Protein-Bagels-4.jpg'),
(35, 'Greek Orzo Salad', '2024-08-16 04:10:19', 'This Greek Orzo Salad recipe is a light and flavorful pasta salad that’s delicious as a side dish or main dish. It’d be perfect with anything you’re grilling this summer!This easy Greek Orzo Salad is similar to my Greek Pasta Salad but with orzo and smaller diced veggies. I love orzo for pasta salads–its shape is great with diced veggies like cucumbers, bell peppers, and tomatoes. Greek flavors like feta cheese and kalamata olives add the perfect zest.&amp;nbsp;And for more healthy pasta salad recipes, don’t miss my Grilled Vegetable Orzo Pasta Salad or Italian Pasta Salad.', 1, '1723781196-Greek-Orzo-Salad-7.jpg'),
(36, 'Broccoli Cauliflower Salad', '2024-08-16 04:10:14', 'This Broccoli Cauliflower Salad recipe is quick and easy, ideal for warmer weather or if you’re looking for a different way to prepare broccoli.I love capers, which pair perfectly with the tangy lemon vinaigrette in this light Broccoli Cauliflower Salad. The dressing makes any vegetable taste delicious and works wonderfully on any veggies like carrots, arugula, etc. This refreshing summer salad is easy to make and packed with fresh, crisp flavors. It is excellent for a picnic, potluck, family gathering, or weeknight family dinners. More broccoli salad recipes, try this Broccoli Salad Recipe with bacon, mayonnaise, red onion and sunflower seeds. Or this Broccoli Sub Salad loaded with your favorite Italian Sub sandwich ingredients.', 1, '1723781232-Broccoli-and-Cauliflower-Salad-with-Capers-and-Lemon-Vinaigrette-5.jpg'),
(37, 'Easy Black Bean Vegetarian Chili with Spiced Yogurt', '2024-08-16 04:10:08', 'This Easy Black Bean Vegetarian Chili topped with Spiced Yogurt takes about 20 minutes to cook but tastes like it simmered for hours.This Black Bean and Corn Chili is a delicious, economical vegetarian chili recipe for weeknight dinners. I use canned black beans, a pantry staple that saves time and money and puree half of the beans to make it creamy without spending a ton of time simmering. Top it with yogurt for added protein and creaminess. Leftovers are great over a baked potato! This is the best black bean chili recipe! More of my favorite chili recipes are Chicken Chili, Vegetarian Pumpkin Chili, White Bean Turkey Chili, and Quick Beef Chili.', 1, '1723781328-Easy-Black-Bean-Vegetarian-Chili-20.jpg'),
(38, 'Lentil Curry', '2024-08-16 04:10:24', 'This easy lentil curry made with curry powder, garam masala, and other spices is perfect over white rice or cauliflower rice as a vegan or vegetarian main or side dish.This red lentil curry is&amp;amp;nbsp;more like a soup than a thick stew and can be made on the stove or in the Instant Pot. It’s perfect served over rice or cauliflower rice. Adding a little lime and cilantro at the end&amp;amp;nbsp;improves and brightens the flavor. Best of all, the curry can stay in&amp;amp;nbsp;the fridge all week for leftovers, and it freezes well. I am always trying to eat more lentils throughout the week because they’re a simple way to achieve my fiber goals (at least 25 grams per day). More lentil recipes I love are this lentil soup and lentil salad.', 1, '1723781356-Lentil-Curry-7.jpg'),
(39, 'Ground Beef and Broccoli Stir Fry', '2024-08-16 04:12:34', 'This easy Ground Beef and Broccoli stir-fry is perfect when you need a quick weeknight dinner!This easy ground beef and broccoli is so much healthier than take-out! Don’t get me wrong, I love Chinese food. But when I am trying to meet my health goals, cooking meals from scratch gives me more control of what goes into my food. Here we use lean ground beef, broccoli and onions simmered with soy sauce, honey, ginger and garlic. If you love beef and broccoli you may also like this Broccoli Beef (made with flank steak). If you prefer chicken, try my Chicken and Broccoli Stir Fry. For other take-out dishes you can make at home check out my General Tso’s Chicken or Spicy Shrimp Fried Rice.', 2, '1723781547-Ground-Beef-and-Broccoli-8.jpg'),
(40, 'Korean Beef Recipe', '2024-08-16 04:17:13', 'An easy and delicious Korean beef recipe is made with ground beef, served over rice with cucumbers and gochujang sauce.If you have a package of ground beef you don’t know what to do with, make this easy Korean beef recipe. You’ll love it! This dish is so popular, it made it to my fan favorite recipes in my Meal Prep Cookbook. More of my favorite Korean-inspired recipes are this Gochujang-Glazed Salmon, Korean Grilled Chicken, and these Gochujang Meatballs. For more stir fries using ground beef, try this Ground Beef and Broccoli recipe.', 2, '1723781594-Korean-Beef-Rice-Bowls-9.jpg'),
(41, 'Marry Me Chicken', '2024-08-16 04:17:08', 'This Marry Me Chicken recipe is a delicious creamy chicken breast recipe with sun-dried tomatoes and spinach in a light cream sauce. Your significant other will definitely want to say “Marry me” after trying this recipe!This one-pan dinner is inspired by the viral “Marry Me Chicken,” made with a few tweaks to lighten it up. It’s such a delicious way to make boring chicken breasts taste amazing! It features the same tender boneless chicken breasts nestled in a tomato cream sauce but replaces heavy cream with cream cheese and half-and-half and adds spinach for an extra veggie. The finished dish is so rich and cozy that you won’t even know the cream is missing. Plus, each serving has 30 grams of protein! More chicken breast recipes you will love, Slow Cooker Chicken Taco Chili, Spinach Stuffed Chicken Breasts and Air Fryer Chicken Bites.', 2, '1723781627-Marry-Me-Chicken-8.jpg'),
(42, 'Slow Cooker Beef Stew', '2024-08-16 04:17:02', 'Slow Cooker Beef Stew is the ultimate comfort food! It’s perfect for those crisp fall evenings or chilly winter days when you crave something hearty and satisfying.Nothing warms the soul quite like a bowl of homemade beef stew, especially one that’s been simmering all day in the slow cooker. This Slow Cooker Beef Stew is here to be your go-to comfort food. It’s packed with tender chunks of beef, veggies, and rich flavors that meld together in the slow cooker. If you don’t have a crockpot, I also have instructions for my stove-top beef stew which is my preferred method of cooking beef stew. But you can’t beat the convenience of making it in the slow cooker for those busy nights!', 2, '1723781667-Slow-Cooker-Beef-Stew-7.jpg'),
(43, 'Soy-Marinated Beef and Broccoli Skewers', '2024-08-16 04:16:57', 'If you love beef and broccoli stir fry, you’ll love these soy-marinated Beef and Broccoli Skewers! A delicious summer dinner idea!We are steak lovers in my house, especially my husband. Anytime he orders Asian food, he usually gets broccoli beef. So, it was no surprise that this Beef and Broccoli Skewer recipe was a big hit in my house! They would be a great addition to whatever you’re grilling for any summer dinner. To make it a meal, serve them over rice with more scallions. If you’re having a BBQ, serve them as an appetizer! More grilled beef recipes you should try are these Steak Kebabs with Chimichurri, Grilled Steak Fajitas or this Grilled Flank Steak with Tomatoes.', 2, '1723781688-Asian-Beef-and-Broccoli-Skewers-13.jpg'),
(44, 'Grilled Shrimp', '2024-08-16 04:16:50', 'This super easy grilled shrimp recipe with garlic butter is a family favorite and a win-win for busy weeknights!Let’s face it–some nights, getting dinner on the table that pleases everyone feels impossible. But these simple grilled shrimp skewers with garlic butter? It’s my secret weapon! This recipe is a lifesaver for any night of the week and&amp;nbsp;so versatile, and no need to make a marinade. You can also cook them on a skillet or in an air fryer (instructions&amp;nbsp;included)! Serve it with grilled steak for an easy surf and turf. Make it a summer meal and serve the shrimp with your favorite side dishes, like coleslaw, grilled corn on the cob, macaroni salad, or Greek orzo salad. And for more of my favorite grilled shrimp skewer recipes, try my Grilled Cilantro Lime Shrimp Skewers and BBQ Shrimp.&amp;nbsp;', 2, '1723781720-Garlic-Butter-Grilled-Shrimp-13.jpg'),
(45, 'Chicken Tikka Masala', '2024-08-16 04:16:22', 'This easy Chicken Tikka Masala is made with tender chunks of boneless chicken breasts cooked in an aromatic, creamy yogurt-tomato sauce.If you need dinner ideas for for chicken breasts, I think you will love this dish! Chicken Tikka Masala is probably the most popular dish on the menu of any Indian restaurant in the States. Ironically, this dish originated in the United Kingdom and is considered Britain’s national dish. My lighter version uses yogurt instead of cream which also adds some extra protein, and is wonderful served over rice. I also have a slow cooker version in the Skinnytaste Fast and Slow Cookbook, this Salmon Tikka Masala and Instant Pot Chicken Tikka Masala (which is also dairy-free).', 2, '1723781757-Chicken-Tikka-Masala-15.jpg'),
(46, 'Blueberry Zucchini Bread', '2024-08-16 23:50:52', 'This moist and delicious Blueberry Zucchini Bread is low-fat, lightly sweetened, and loaded with summer zucchini and blueberries. Perfect for breakfast or as a snack!When I was recently in Connecticut, I went to this cute country kitchen on an apple orchard called Lost Acres Orchard, and I tried their blueberry zucchini bread, which inspired this recipe. I omitted the nuts and added blueberries to my zucchini bread recipe, which turned out fantastic! Other zucchini bread recipes you may enjoy are this Chocolate Zucchini Bread and Chocolate Chip Zucchini Bread.', 1, '1723852246-Blueberry-Zucchini-Bread-10.jpg'),
(48, 'Crock Pot Pork Roast', '2024-08-17 20:04:06', 'This easy Crock Pot Pork Roast with Mushrooms and Polenta is packed with flavor and is incredibly easy to prepare.This Crock Pot Pork Roast recipe definitely won’t disappoint. Cooking the pork low and slow all day with soy sauce, broth, honey and garlic, transforms it into a fall-apart tender and tasty dinner. Then it’s served over creamy polenta, it tastes so good. I love making crock pot dinners because they’re so easy. Some of my favorite pork slow cooker meals are: Slow Cooker Pernil, Slow Cooker Sweet Barbacoa Pork just to name a few!', 2, '1723925045-Easy-Slow-Cooker-Pork-Roast-with-Mushrooms-and-Polenta-7.jpg'),
(49, 'Slow Cooker Pulled Pork', '2024-08-17 20:01:11', 'This easy, “set it and forget it” Slow Cooker Pulled Pork comes out so tender and juicy, made from scratch with my homemade BBQ sauce.BBQ pulled pork is one of those dishes you can serve all year round. It’s perfect for feeding a crowd at potlucks or large gatherings and doesn’t require much time in the kitchen. I created this leaner pulled pork recipe using boneless pork loin roast and&nbsp;homemade BBQ sauce, which gave me complete control of what was added. Try these&nbsp;pork carnitas for Mexican pulled pork, and check out my&nbsp;slow cooker recipes&nbsp;for more&nbsp;easy dinner ideas.', 2, '1723924871-pulled-pork-08.jpg'),
(51, 'Almond Cake', '2024-08-17 19:58:43', 'The best flour-less almond cake recipe ever! It’s gluten-free, with a fantastic light texture, and it doesn’t even need any oil or butter. Best of all, you only need 5 ingredients to make it!When my family gets together for holidays, birthdays, or just because, everyone chips in and brings a dish. It’s usually a given that my cousin Nina will bring&amp;nbsp;dessert. At one family party, Nina brought this healthy almond cake and I couldn’t get over how delicious it was. The original almond cake recipe comes from baking goddess Dorie Greenspan’s cookbook,&amp;nbsp;Baking Chez Moi. The only changes I made to Dorie’s recipe were reducing the sugar and egg yolks. It is perfection!', 1, '1723924723-Almond-Cake-19.jpg'),
(53, 'Chocolate Oat Flour Banana Bread', '2024-08-17 19:59:46', 'This Chocolate Oat Flour Banana Bread is moist, chocolatey and loaded with chocolate chips. If you love chocolate and banana bread, you’ll enjoy this gluten-free banana bread!I am excited to share this Chocolate Banana Bread with oat flour. I have been testing the recipe for a few weeks, and it is finally perfect. My daughter Madison loved it! The oat flour gives it a moist consistency, similar to baked oatmeal, and adds more nutrients than wheat flour. Plus, it’s naturally gluten-free. If you want something more traditional, try my other banana bread recipes like my Banana Nut Bread, Blueberry Banana Bread, and Gluten-Free Banana Nut Bread.', 1, '1723924786-Chocolate-Banana-Oat-Bread-6.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`) VALUES
(1, 'bob_xy@qq.com', '$2y$10$X20iVgmFM9U5GeOqm6Nn6eqdHnE3QLbtMGGkcXBVyWlfhjWTahEwm'),
(2, 'xuh34568@myumanitoba.ca', '$2y$10$BJ1xPKsLxTcdrOUlKHo0bOVvfMYeEGq5v.qV5Qg60uKBIR9p6gh3.'),
(4, 'admin@admin.ca', '$2y$10$KfQKZbj1Q1Iq3VsjZb4tduiVsLbIoXNIqo1WTwUQTYKrvEJ7GMAe.');

-- --------------------------------------------------------

--
-- Table structure for table `vegetarian`
--

CREATE TABLE `vegetarian` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `vegetarian`
--

INSERT INTO `vegetarian` (`id`, `name`) VALUES
(1, 'Vegetarian'),
(2, 'Non-Vegetarian');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_post` (`post_id`);

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `vegetarian_id` (`vegetarian_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `vegetarian`
--
ALTER TABLE `vegetarian`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `vegetarian`
--
ALTER TABLE `vegetarian`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_post` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `posts`
--
ALTER TABLE `posts`
  ADD CONSTRAINT `posts_ibfk_1` FOREIGN KEY (`vegetarian_id`) REFERENCES `vegetarian` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
