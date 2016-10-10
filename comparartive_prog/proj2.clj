
;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;
;;                                                       ;;
;;                                                       ;;
;;			            Clojure project        
;;                                                       ;;
;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;

;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;
;; part 1
;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;

;; Pair function
(defn pair? [x]
	(cond
		(= (first x) nil) false
		(= (first (rest x)) nil) false
		(not= (second (rest x)) nil) false
		:else true
	)
)

;; Alist function
(defn alist? [x]
	(cond
		(empty? x) true
		(pair? (first x)) (alist? (rest x))
		:else false

	)
)

;; get-all-pairs  function

(defn get-all-pairs [key seq]
		(cond
			(empty? seq)
				()
			(= key (first (first seq)))
		    	(concat (list (first seq)) (get-all-pairs key (rest seq)))
			:else
				(get-all-pairs key (rest seq))
		)
)

;; get-first-pair function

(defn get-first-pair [key seq]
	(cond
		(empty? seq) nil
		(= (first (first seq)) key)
			(first seq)
		:else
			(get-first-pair key (rest seq))

	)
)

;; del-all-pairs function
(defn del-all-pairs [key seq]
	(cond
		(empty? seq) ()
		(not= key (first (first seq))) (concat (list (first seq)) (del-all-pairs key (rest seq)))
		:else (del-all-pairs key (rest seq))
	)
)

;; del-first-pair function

(defn del-first-pair [key seq]
	(let [ lst '() ]
		(cond
			(= key (first (first seq))) (concat lst (rest seq))
			:else (concat (cons (first seq) lst) (del-first-pair key (rest seq)))

		)

	)
)

;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;
;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;
;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;

;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;
;; part 2
;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;

;; SOME HELPER FUNCTIONS

;; Is number function
(defn isnumber? [n]
	(cond
		(or (float? n) (integer? n)) true
		:else false
	)
)
; customized function third for convenience
(defn third [seq]
	(cond
		(empty? seq) '()
        :else (second (rest seq))
    )
)

;; Is variable function

(defn isvar? [n]
	(cond
		(not (symbol? n)) false
		:else true
	)
)




;; Simple EXPRESSION EVALUATOR
;; This function simply evaluates two operands with one operator
;; no variable or deep evaluation will work in this function

(defn seval [e]
	(cond
		(number? e) e
		:else
			(let [ left (first e)
				   op (second e)
				   right (third e)
				 ]
				(cond
					(= op '+) (+ left right)
					(= op '-) (- left right)
					(= op '*) (* left right)
					(= op '/) (float (/ left right))
				)
			)
	)

)

;; Var evaluator (one level evaluator not a deep evaluator)
;; Evaluates the variables -->  Can put the value of x in the evaluation

(defn vareval [expr environment]
	(let [	left  (first expr)
			op    (second expr)
			right (third expr)
		 ]
		(cond
			(and (isvar? left) (isvar? right))
				(cond
					(alist? environment)
						(cond
							(and (= left (first (get-first-pair (symbol left) environment))) (= right (first (get-first-pair (symbol right) environment))) )
									(seval  (list (second (get-first-pair (symbol left) environment)) op  (second (get-first-pair (symbol right) environment))))
							:else nil

						)
					:esle nil
				)
			(and (isvar? left) (isnumber? right))
				(cond
					(alist? environment)
						(cond
							(= left (first (get-first-pair (symbol left) environment)))
									(seval  (list (second (get-first-pair (symbol left) environment)) op right))
							:else nil

						)
					:esle nil
				)
			(and (isnumber? left) (isvar? right))
				(cond
					(alist? environment)
						(cond
							(= right (first (get-first-pair (symbol right) environment)))
									(seval  (list left op (second (get-first-pair (symbol right) environment))))
							:else nil

						)
					:esle nil
				)
			(and (isnumber? left) (isnumber? right)) (seval (list left op right) )
			:else nil

		)


	)
)

;; Deep evaluator (multi level expression evaluator)
;; Can evaluate the deep expression (brackets)

(defn myeval [expr environment]
	(let [ left (first expr)
		   op (second expr)
		   right (third expr)
		 ]
		(cond
			(and (seq? left) (seq? right))
				(vareval ( list (myeval left environment) op (myeval right environment)) environment)
			(and (not (seq? right)) (seq? left))
				(vareval ( list (myeval left environment) op right) environment)
			(and (seq? right) (not (seq? left)))
				(vareval ( list left op (myeval right environment)) environment)
			:else (vareval expr environment)
		)
    )

)
