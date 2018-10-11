<div class="audio">
  <article>
    <h1 class="audio__title">$Title</h1>
    <% if $AudioType == 'Upload' %>
      <audio controls="controls" controlsList="nodownload" id="player" class="audio__player">
        <source src="$Audio.Link"/>
        <p> Your browser doesn't support the audio tag </p>
      </audio>
    <% else %>
      $AudioEmbedCode
    <% end_if %>
    <div class="audio__summary">$AudioSummary.LimitCharacters(200)</div>
  </article>
  <% if $TranscriptTitle && $Transcript %>
    <div class="audio__transcript">
      <h2 class="audio__transcript-title">$TranscriptTitle</h2>
      <p class="audio__transcript-description">$Transcript</p>
    </div>
  <% end_if %>
</div>
