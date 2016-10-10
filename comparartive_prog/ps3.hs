-- Problem set 3  -- Has

-- ****PROBLEM 1****
is_bit :: Integer -> Bool

is_bit x  = if x == 1 || x == 0
			  then True
			  else False

 ---------------------

 -- ****PROBLEM 2****
flip_bit :: Integer -> Integer

flip_bit x
	| x == 0 = 1
    | x == 1 = 0
    | otherwise = error "The input is not a bit"

 ---------------------

 -- ****PROBLEM 3****

-- 3.A) is_bit_seq1 function (Guarded command)
is_bit_seq1 :: [Integer] -> Bool

is_bit_seq1 x
	| x == [] = True
	| is_bit (head x) = is_bit_seq1 (tail x)
	| otherwise = False

 ---------------------

--3.B) is_bit_seq2 function (Using if then else)
is_bit_seq2 :: [Integer] -> Bool

is_bit_seq2 x =
	if (x == [])
	   then True
	else if is_bit (head x)
	   		then is_bit_seq2 (tail x)
	   	 else False
-----------------------

--3.C) is_bit_seq3 function (using all)
is_bit_seq3 :: [Integer] -> Bool

is_bit_seq3 x = all (is_bit) x

------------------------

 -- ****PROBLEM 4****

--4.A) invert_bits1 function (Using recursion)
invert_bits1 :: [Integer] -> [Integer]

invert_bits1 x
	| x == [] = []
	| otherwise = flip_bit (head x) : (invert_bits1 (tail x))

 ---------------------

 --4.B) invert_bits2 function (Using map function)
invert_bits2 :: [Integer] -> [Integer]

invert_bits2 x = map (flip_bit ) x

-----------------------

--4.C) invert_bits3 function (List comprehension)
invert_bits3 :: [Integer] -> [Integer]

invert_bits3 x = [flip_bit n | n <- x]

 -- ****PROBLEM 5****
 -- Counting number of 1's and 0's

-- Helper function counting # of 1's
count_1 :: [Integer] -> Integer
count_1 x =
	if x /= []
       then if (head x) == 1
			    then 1 + count_1 (tail x)
			else count_1 (tail x)
	else 0

-- Helper function counting # of 1's
count_0 :: [Integer] -> Integer
count_0 x =
	if x /= []
       then if (head x) == 0
			    then 1 + count_0 (tail x)
			else count_0 (tail x)
	else 0

-- main function
bit_count :: [Integer] -> (Integer,Integer)

bit_count x = (count_0 x,count_1 x)
 ---------------------

 -- ****PROBLEM 6****
-- Helper function appending 1 to a list
add1 :: [Integer] -> [Integer]
add1 x = 1 : x

-- Helper function appending 1 to a list
add0 :: [Integer] -> [Integer]
add0 x = 0 : x

-- Concatenate two lists
mycons :: [[Integer]] -> [[Integer]] -> [[Integer]]
mycons x y = x ++ y

-- Main function
all_bit_seqs :: Integer -> [[Integer]]
all_bit_seqs n
			 | n == 0 = []
			 | n == 1 = [[0],[1]]
			 | otherwise = (mycons [add1 x | x <- all_bit_seqs (n - 1)] [add0 x | x <- all_bit_seqs (n - 1)])

 ---------------------
