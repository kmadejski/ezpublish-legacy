{switch name=sw match=$level}
{case match=1}
{pdf(header, hash( level, 1,
                   text, $content,
		   size, 26,
		   align, center ) )}
{/case}
{case match=2}
{pdf(header, hash( level, 2,
                   text, $content,
		   size, 22 ) )}
{/case}
{case match=3}
{pdf(header, hash( level, 3,
                   text, $content,
		   size, 18 ) )}
{/case}
{case match=4}
{pdf(header, hash( level, 4,
                   text, $content,
		   size, 14 ) )}
{/case}
{case}
{pdf(header, hash( level, 5,
                   text, $content,
		   size, 26 ) )}
{/case}
{/switch}
