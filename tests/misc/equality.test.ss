(if (equal? 1 2)
    (error! "equal? 1 2 was true")
    ())

(if (not (equal? 1 1))
    (error! "equal? 1 1 was false")
    ())

(if (equal? '(1 2 3) '(1 2 2))
    (error! "equal? failed to compare lists recursively")
    ())

(if (not (equal? '(1 2 3) '(1 2 3)))
    (error! "equal? on equal lists failed")
    ())