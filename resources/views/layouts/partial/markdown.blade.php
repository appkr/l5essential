<div class="modal fade" id="md-modal" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">
          <span aria-hidden="true">&times;</span>
          <span class="sr-only">Close</span>
        </button>
        <h4 class="modal-title">{{ trans('common.cheat_sheet') }}</h4>
      </div>

      <div class="modal-body table-responsive">
        <table class="table">
          <tr>
            <th>Markdown</th>
            <th>Compiled HTML</th>
          </tr>
          <tr>
            <td># heading 1</td>
            <td>{!! markdown('# heading 1') !!}</td>
          </tr>
          <tr>
            <td>## heading 2</td>
            <td>{!! markdown('## heading 2') !!}</td>
          </tr>
          <tr>
            <td>###### heading 6</td>
            <td>{!! markdown('###### heading 6') !!}</td>
          </tr>
          <tr>
            <td>Paragraphs are separated<br/><br/>by a blank line.</td>
            <td>{!! markdown('Paragraphs are separated

by a blank line.') !!}</td>
          </tr>
          <tr>
            <td>Let 2 spaces at the end of a line to do a&nbsp;&nbsp;<br/>
line break</td>
            <td>{!! markdown('Let 2 spaces at the end of a line to do a
line break') !!}</td>
          </tr>
          <tr>
            <td>*emphasis*, _emphasis_, **strong**, __strong__, ~~strike~~, `inline code`</td>
            <td>{!! markdown('*emphasis*, _emphasis_, **strong**, __strong__, ~~strike~~, `inline code`') !!}</td>
          </tr>
          <tr>
            <td>[Link Text](http://example.com)</td>
            <td>{!! markdown('[Link Text](http://example.com)') !!}</td>
          </tr>
          <tr>
            <td>![Alt Text](http://lorempixel.com/100/100/)</td>
            <td>{!! markdown('![Alt Text](http://lorempixel.com/100/100/)') !!}</td>
          </tr>
          <tr>
            <td>- apples<br/>- oranges<br/>- pears</td>
            <td>{!! markdown('- apples
- oranges
- pears') !!}</td>
          </tr>
          <tr>
            <td>1. apples<br/>2. oranges<br/>3. pears</td>
            <td>{!! markdown('1. apples
2. oranges
3. pears') !!}</td>
          </tr>
          <tr>
            <td>```<br/>
              public function clear(key) {<br/>
&nbsp;&nbsp;&nbsp;&nbsp;return this.cache.flush(key);<br/>
}<br/>
```<br/>
              <small class="text-muted">3 tilde(~~~) is also possible.</small>
            </td>
            <td>{!! markdown('```
public function clear(key) {
    return this.cache.flush(key);
}
```') !!}</td>
            </td>
          </tr>
          <tr>
            <td>Markdown | Less | Pretty<br/> --- | --- | --:<br/> *Still* | `renders` | **nicely**<br/> 1 | 2 | 3<br/>
              <small class="text-muted">Colons(:) can be used to align columns.</small>
            </td>
            <td>{!! markdown('Markdown | Less | Pretty
--- | --- | --:
*Still* | `renders` | **nicely**
1 | 2 | 3') !!}</td>
          </tr>
          <tr>
            <td>>Blockquotes are very handy in email to emulate reply text.<br/> > This line is part of the same
              quote.<br/> <br/> Quote break.<br/> <br/> > This is a very long line that will still be quoted properly
              when it wraps. Oh boy let's keep writing to make sure this is long enough to actually wrap for everyone.
              Oh, you can *put* **Markdown** into a blockquote.
            </td>
            <td>{!! markdown('>Blockquotes are very handy in email to emulate reply text.
>This line is part of the same quote.

Quote break.
> This is a very long line that will still be quoted properly when it wraps. Oh boy let\'s keep writing to make sure this is long enough to actually wrap for everyone. Oh, you can *put* **Markdown** into a blockquote.') !!}</td>
          </tr>
          <tr>
            <td>---</td>
            <td>{!! markdown('---') !!}</td>
          </tr>
          <tr>
            <td>[![IMAGE ALT TEXT HERE](http://img.youtube.com/vi/dhI9bsQwFRw/1.jpg)](http://www.youtube.com/watch?v=dhI9bsQwFRw)
            </td>
            <td>{!! markdown('[![IMAGE ALT TEXT HERE](http://img.youtube.com/vi/dhI9bsQwFRw/1.jpg)](http://www.youtube.com/watch?v=dhI9bsQwFRw)') !!}</td>
          </tr>
          <tr>
            <td>That's some text with a footnote.[^1]<br/>[^1]: And that's the footnote.</td>
            <td>{!! markdown('That\'s some text with a footnote.[^1]

[^1]: And that\'s the footnote.') !!}</td>
          </tr>
          <tr>
            <td>\_Markdown tries to translate paired underscores as an emphasis. Use back slash to escape_</td>
            <td>{!! markdown('\_Markdown tries to translate paired underscores as an emphasis. Use back slash to escape_') !!}</td>
          </tr>
        </table>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
