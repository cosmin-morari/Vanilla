CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `title` text NOT NULL,
  `description` text NOT NULL,
  `price` int(11) NOT NULL,
  `imageSource` text NOT NULL
)

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `date` text NOT NULL,
  `customer_details` text NOT NULL,
  `purchased_products` text NOT NULL,
  `total_price` int(11) NOT NULL
)