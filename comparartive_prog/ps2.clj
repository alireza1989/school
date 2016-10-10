
;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;
;
;
;                    PS2 - clojure
;
;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;
;; PROBLEM #1
(defn make-double-cheese [pizza]
	(cond
		(= false (seq? pizza)) '()
		(and (= 'cheese (first pizza)) (= 'cheese (first (rest pizza)))) pizza
		(= 'cheese (first pizza)) (cons 'cheese pizza)
		:else (cons 'cheese (cons 'cheese pizza))
	)

)

;;;;;;;;;;;;;;;;;;;;;;;;;;;;;


;; PROBLEM #2

;; Length of a list used as a helper function

(defn length [seq]
	(cond
		(empty? seq) 0
		:else (+ 1 (length (rest seq)))

	)

)
;; main last function
(defn my-last [lst]
	(cond
		(= 0 (length lst)) nil
		(= 1 (length lst)) (first lst)
		:esle(my-last (rest lst))

	)
)

;;;;;;;;;;;;;;;;;;;;;;;;;;;;;


;; PROBLEM #3
(defn is-bit? [x]
	(cond
		(or (= x 0 ) (= x 1)) true
		:else false
	)
)

;;;;;;;;;;;;;;;;;;;;;;;;;;;;;


;; PROBLEM #4
(defn is-bit-seq? [x]
	(cond
		(empty? x) true
		(and ( is-bit? (first x)) (is-bit-seq? (rest x))) true
		:else false
	)

)

;;;;;;;;;;;;;;;;;;;;;;;;;;;;;


;; PROBLEM #5


; ADD one to list elements
(defn add1 [x]
	(cond
	 	(empty? x) '()
	 	:else (cons (cons 1 (first x)) (add1 (rest x)))


	)
)

; ADD zero to the list elements
(defn add0 [x]
	(cond
	 	(empty? x) '()
	 	:else (cons (cons 0 (first x)) (add0 (rest x)))


	)
)

; Concatenate the list which 1 is added to the front of elements
; of input list with list which 0 is added to the front of elements
(defn concatList [x y]
    (cond
		(empty? y) x
		:else (concatList (cons (first y) x) (rest y))
	)
)

; Generating all bit sequences with length n recursively
; Using helper functions add0, add1 and concatList
(defn all-bit-seqs [n]
 	(cond
 		(= n 1) '((0) (1))
 		:else (concatList (add1 (all-bit-seqs (- n 1) ) )  (add0 (all-bit-seqs (- n 1)  ) ) )
 	)
)

;;;;;;;;;;;;;;;;;;;;;;;;;;;;;

;; PROBLEM #6

;; DEEP COUNT SYMBOL
(defn deep-count-symbols [seq]
	(cond
		(empty? seq) 0
		(seq? (first seq)) (+ (deep-count-symbols (first seq)) (deep-count-symbols (rest seq)))
		(symbol? (first seq)) (+ 1 (deep-count-symbols (rest seq)))
		:else (deep-count-symbols (rest seq))
	)
)

;; DEEP COUNT NUMBERS
(defn deep-count-numbers [seq]
	(cond
		(empty? seq) 0
		(seq? (first seq)) (+ (deep-count-numbers (first seq)) (deep-count-numbers (rest seq)))
		(number? (first seq)) (+ 1 (deep-count-numbers (rest seq)))
		:else (deep-count-numbers (rest seq))
	)
)

;;;;;;;;;;;;;;;;;;;;;;;;;;;;;
