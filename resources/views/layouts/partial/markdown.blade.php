<div class="modal fade" id="md-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">
          <span aria-hidden="true">&times;</span>
          <span class="sr-only">Close</span>
        </button>
        <h4 class="modal-title">Markdown Cheatsheet</h4>
      </div>

      <div class="modal-body table-responsive">
        <p>Markdown is a plain text formatting syntax designed so that it can be converted to rich text, and is the
          future of documenting things down in online. Sites such as <a href="https://github.com/">Github</a>, <a
            href="http://stackexchange.com/">Stack Exchange</a>, <a href="http://sourceforge.net/">SourceForge</a>, ...
          are using Markdown extensively.</p>
        <table class="table">
          <tr>
            <th>Markdown you should write</th>
            <th>Text viewed in a brower</th>
          </tr>
          <tr>
            <td># heading 1</td>
            <td><h1>heading 1</h1></td>
          </tr>
          <tr>
            <td>## heading 2</td>
            <td><h2>heading 2</h2></td>
          </tr>
          <tr>
            <td>###### heading 6</td>
            <td><h6>heading 6</h6></td>
          </tr>
          <tr>
            <td>Paragraphs are separated<br/>by a blank line.</td>
            <td><p>Paragraphs are separated by a blank line.</p></td>
          </tr>
          <tr>
            <td>Let 2 spaces at the end of a line to do a&nbsp;&nbsp;<br/>line break</td>
            <td><p>Let 2 spaces at the end of a line to do a<br/>line break</p></td>
          </tr>
          <tr>
            <td>*emphasis*, _emphasis_, **strong**, __strong__, ~~strike~~, `inline code`</td>
            <td>
              <p><em>emphasis</em>, <em>emphasis</em>, <strong>strong</strong>, <strong>strong</strong>,
                <del>strike</del>
                , <code>inline code</code></p>
            </td>
          </tr>
          <tr>
            <td>[Link Text](http://example.com)</td>
            <td><p><a href="http://example.com">Link Text</a></p></td>
          </tr>
          <tr>
            <td>![Alt Text](http://lorempixel.com/100/100/)</td>
            <td><p><img alt="Alt Text" src="http://lorempixel.com/100/100/"
                        style="max-width:100%;"></p></td>
          </tr>
          <tr>
            <td>Unordered List:<br/>- apples<br/>- oranges<br/>- pears<br>
              <small class="text-muted">*, + are also possible</small>
            </td>
            <td><p>Unordered List:</p>
              <ul>
                <li>apples</li>
                <li>oranges</li>
                <li>pears</li>
              </ul>
            </td>
          </tr>
          <tr>
            <td>Ordered List:<br/>1. apples<br/>2. oranges<br/>3. pears</td>
            <td><p>Ordered List:</p>
              <ol>
                <li>apples</li>
                <li>oranges</li>
                <li>pears</li>
              </ol>
          </tr>
          <tr>
            <td>
              ```
              public function clear(key) {
              return this.cache.flush(key);
              }```
              <small class="text-muted">~~~ more than 3 tilde is also possible</small>
            </td>
            <td><pre class="prettyprint"><code>public function clear(key) {
                  return this.cache.flush(key);
                  }</code></pre>
            </td>
            </td>
          </tr>
          <tr>
            <td>Markdown | Less | Pretty<br/> --- | --- | --:<br/> *Still* | `renders` | **nicely**<br/> 1 | 2 | 3<br/>
              <small class="text-muted">Colons(:) can be used to align columns.</small>
            </td>
            <td>
              <table>
                <thead>
                <tr>
                  <th>Markdown</th>
                  <th>Less</th>
                  <th align="right">Pretty</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                  <td><em>Still</em></td>
                  <td><code>renders</code></td>
                  <td align="right"><strong>nicely</strong></td>
                </tr>
                <tr>
                  <td>1</td>
                  <td>2</td>
                  <td align="right">3</td>
                </tr>
                </tbody>
              </table>
            </td>
          </tr>
          <tr>
            <td>>Blockquotes are very handy in email to emulate reply text.<br/> > This line is part of the same
              quote.<br/> <br/> Quote break.<br/> <br/> > This is a very long line that will still be quoted properly
              when it wraps. Oh boy let's keep writing to make sure this is long enough to actually wrap for everyone.
              Oh, you can *put* **Markdown** into a blockquote.
            </td>
            <td>
              <blockquote><p>Blockquotes are very handy in email to emulate reply text. This line is part of the same
                  quote.</p></blockquote>
              <p>Quote break.</p>
              <blockquote><p>This is a very long line that will still be quoted properly when it wraps. Oh boy let\'s
                  keep writing to make sure this is long enough to actually wrap for everyone. Oh, you can <em>put</em>
                  <strong>Markdown</strong> into a blockquote. </p></blockquote>
            </td>
          </tr>
          <tr>
            <td>Three or more...<br/><br/>---<br>
              <small calss="text-muted">***, ___ are also possible</small>
            </td>
            <td><p>Three or more...</p>
              <hr/>
            </td>
          </tr>
          <tr>
            <td>[![IMAGE ALT TEXT
              HERE](//img.youtube.com/vi/dhI9bsQwFRw/0.jpg)](//www.youtube.com/watch?v=dhI9bsQwFRw)
            </td>
            <td><p><a href="//www.youtube.com/watch?v=dhI9bsQwFRw"><img alt="IMAGE ALT TEXT HERE"
                                                                        src="//img.youtube.com/vi/dhI9bsQwFRw/0.jpg"
                                                                        style="max-width:100%;"></a></p></td>
          </tr>
          <tr>
            <td># heading {#id}<br/>[Link text](#link) {.class}<br/>
              <small class="text-muted">With {#id} feature you can make anchor on the same page like:<br/> - [heading
                with id](#id)<br/>...<br/>...<br/>&lt;a name="id"&gt;&lt;/a&gt;<br/>## heading with id
              </small>
            </td>
            <td><h1 id="id">heading</h1>

              <p><a href="#link" class="class">Link text</a></p></td>
          </tr>
          <tr>
            <td>Apple<br/>: Pomaceous fruit of plants of the genus Malus in the family Rosaceae.<br/><br/>Orange<br/>:
              The fruit of an evergreen tree of the genus Citrus.
            </td>
            <dl>
              <dt>Apple</dt>
              <dd>Pomaceous fruit of plants of the genus Malus in the family Rosaceae.</dd>
              <dt>Orange</dt>
              <dd>The fruit of an evergreen tree of the genus Citrus.</dd>
            </dl>
            </td>
          </tr>
          <tr>
            <td>That's some text with a footnote.[^1]<br/>[^1]: And that's the footnote.</td>
            <td><p>That's some text with a footnote.<sup id="fnref1:1"><a href="#fn:1" class="footnote-ref">1</a></sup>
              </p>

              <div class="footnotes">
                <hr>
                <ol>
                  <li id="fn:0"><p>And that's the footnote.&nbsp;<a href="#fnref1:0" rev="footnote"
                                                                    class="footnote-backref">↩</a></p></li>
                </ol>
              </div>
            </td>
          </tr>
          <tr>
            <td>\_Markdown will translate me as an emphasis. But I want to be just an underscore_</td>
            <td><p>_Markdown will translate me as an emphasis. But I want to be compiled just as an underscore_</p></td>
          </tr>
        </table>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
