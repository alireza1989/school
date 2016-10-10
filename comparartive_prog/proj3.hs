-- #############################################################
--
-- 		                 Project 3 - Haskell 
--
-- #############################################################

-- Defining Bit as a data type
data Bit = Zero | One
    deriving (Show, Eq)

--  Q1 flipBit implementation

flipBit :: Bit -> Bit
flipBit n
	| n == Zero = One
	| n == One  = Zero
	| otherwise = error "The input is not a bit"


--  Q2 invert implementation

invert :: [Bit] -> [Bit]
invert [] = []
invert l = [flipBit n | n <- l]



--  Q3 All bitSeq implementation

-- Helper function appending 1 to a list
addOne :: [Bit] -> [Bit]
addOne x = One : x

-- Helper function appending 0 to a list
addZero :: [Bit] -> [Bit]
addZero x = Zero : x

-- Concatenate two lists
mycons :: [[Bit]] -> [[Bit]] -> [[Bit]]
mycons x y = x ++ y

-- Main function
all_bit_seqs :: Int -> [[Bit]]
all_bit_seqs 0 = []
all_bit_seqs n = sequence (replicate n [Zero,One])

-- Q4  bitSum1  implementation

-- Helper function -- that returns integer 1 if n is One otherwise returns 0

isOne :: Bit -> Int
isOne n = if n == One then 1 else 0

-- bitsum1 main

bitSum1 :: [Bit] -> Int
bitSum1 l = if l == []
			then 0
			else sum ([ isOne n | n <- l])

-- Q5  bitSum2  implementation
-- Helper function isOne2 -- This handdles the maybe input
isOne2 :: Maybe Bit -> Int
isOne2 n = if (n == Just Zero)  || (n == Nothing)
		  then 0
		  else 1


bitSum2 :: [Maybe Bit] -> Int
bitSum2 l = if l == []
			then 0
			else sum ([ isOne2 n | n <- l])

-- Q6  bitSum2  implementation

--- List Data type
data List a = Empty | Cons a (List a)
    deriving Show

-- toList Function implementation

toList :: [a] -> List a
toList l | length(l) == 0 = Empty
		 | otherwise = Cons (head l) (toList (tail l))

-- Q7 toHaskellList implementation

toHaskellList :: List a -> [a]
toHaskellList Empty = []
toHaskellList (Cons val lst) = val : (toHaskellList lst)

-- Q8 append implementation

append :: List a -> List a -> List a
append Empty lstb = lstb
append (Cons va la) lstb = Cons va (append la lstb)

-- Q9 removeAll f L implementation
removeAll :: (a -> Bool) -> List a -> List a
removeAll f Empty = Empty
removeAll f (Cons a la) = if (f a == True)
	                      then removeAll f la
	                      else Cons a (removeAll f la)

-- Q10 sort fucntion implementation

sort :: Ord a => List a -> List a
sort Empty     = Empty
sort (Cons val la) = append smalls (append (Cons val Empty) bigs)
                   where smalls = sort (removeAll (>= val) la)
                         bigs  = sort (removeAll  (<= val)  la)



-- Q11 best_partition fucntion implementation
-- ****This function is using brute force algorthim and due to the in efficiency only works up to list of size 4 numbers as input ****

-- Helper function that generates all the subsets of a list
-- I took the code from: http://stackoverflow.com/questions/19772427/haskell-generate-subsets

-- Helper function that construct a list of all sebsets of a list

subsets :: [a] -> [[a]]
subsets []  = [[]]
subsets (x:xs) = subsets xs ++ map (x:) (subsets xs)


-- A helper function that gets a list of all subsets and only return the subsets with size two
-- The list contains all the partitions


pairSet :: [[a]] -> [[a]]
pairSet [] = []
pairSet l = if length (head l) == 2
	        then head l : (pairSet (tail l))
	        else pairSet (tail l)


-- A helper function to filter the set of pairs


filterpairs :: [a] -> [[[a]]] -> [[[a]]]
filterpairs lst [] = []
filterpairs lst l = if ((length ((head l) !! 0)) + (length ((head l) !! 1))) == length(lst)
				   then (head l) : (filterpairs lst (tail l))
				   else filterpairs lst (tail l)



-- making a list of partions and their suntraction values


all_partitions :: (Num a) => [[[a]]] -> [(a, [a], [a])]
all_partitions [] = []
all_partitions l = ( abs (sum ((head l) !! 0) - sum ((head l) !! 1)) , (head l) !! 0, (head l) !! 1) : (all_partitions (tail l))

-- helper function of sort.  I took it from course website
quicksort :: Ord a => [a] -> [a]
quicksort []     = []
quicksort (x:xs) = smalls ++ [x] ++ bigs
                    where smalls = quicksort [n | n <- xs, n <= x]
                          bigs   = quicksort [n | n <- xs, n > x]


-- main function
best_partition :: [Int] -> (Int, [Int], [Int])
best_partition lst =  minimum (all_partitions (filterpairs lst (pairSet (subsets ( quicksort (subsets (quicksort lst )))))))
